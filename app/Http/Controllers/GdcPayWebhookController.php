<?php

namespace App\Http\Controllers;

use App\Models\BranchQrisPayment;
use App\Models\QrisPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GdcPayWebhookController extends Controller
{
    /**
     * GDC Pay (SNAP) meminta access token dari domain kita sebelum mengirim
     * notifikasi pembayaran. Endpoint ini sekadar menerbitkan token sementara;
     * belum memverifikasi identitas GDC (lihat catatan README).
     */
    public function issueAccessToken(Request $request)
    {
        Log::info('gdcpay.access_token_requested', ['ip' => $request->ip()]);

        return response()->json([
            'responseCode' => '2007300',
            'responseMessage' => 'Successful',
            'accessToken' => (string) Str::uuid(),
            'tokenType' => 'Bearer',
            'expiresIn' => '900',
        ]);
    }

    public function qrisNotification(Request $request)
    {
        $referenceNo = $request->input('originalPartnerReferenceNo') ?? $request->input('partnerReferenceNo');
        $gdcReferenceNo = $request->input('originalReferenceNo') ?? $request->input('referenceNo');

        $payment = QrisPayment::where('reference_no', $referenceNo)
            ->orWhere('gdc_reference_no', $gdcReferenceNo)
            ->first();

        if ($payment) {
            $status = strtolower($request->input('latestTransactionStatus', ''));
            $paidAmount = isset($request->input('amount')['value'])
                ? (int) round((float) $request->input('amount')['value'])
                : null;

            if (in_array($status, ['success', 'paid', '00'], true)) {
                if ($paidAmount !== null && ! $payment->receivedAmountMatches($paidAmount)) {
                    $payment->update(['status' => 'failed']);
                    Log::warning('gdcpay.qris_notification.amount_mismatch', ['reference' => $referenceNo]);
                } else {
                    $payment->markAsPaid();
                }
            } else {
                $payment->update(['status' => 'failed']);
            }

            return response()->json(['responseCode' => '2007300', 'responseMessage' => 'Successful']);
        }

        // Bukan punya penjualan MySifa sendiri -- cek apakah ini punya salah satu
        // cabang (partnerReferenceNo diprefix "{kode_cabang}-..." saat dibuat di
        // BranchQrisController::generate()).
        $branchPayment = BranchQrisPayment::where('prefixed_reference_no', $referenceNo)
            ->orWhere('gdc_reference_no', $gdcReferenceNo)
            ->first();

        if (! $branchPayment) {
            Log::warning('gdcpay.webhook.payment_not_found', ['reference' => $referenceNo]);

            return response()->json(['responseCode' => '4042500', 'responseMessage' => 'Payment not found'], 404);
        }

        $this->applyBranchNotification($branchPayment, $request);

        return response()->json(['responseCode' => '2007300', 'responseMessage' => 'Successful']);
    }

    /**
     * Update status BranchQrisPayment dari notifikasi GDC, lalu teruskan hasilnya
     * ke callback_url cabang pemilik (server-to-server, bukan redirect customer).
     * Gagal meneruskan TIDAK membuat kita membalas error ke GDC -- status di sisi
     * hub sudah benar tersimpan, cabang akan tetap dapat statusnya lewat polling
     * (BranchQrisController::status()) kalau forward ini sempat gagal.
     */
    private function applyBranchNotification(BranchQrisPayment $branchPayment, Request $request): void
    {
        $status = strtolower($request->input('latestTransactionStatus', ''));
        // Observasi nyata dari sandbox GDC: nominal yang sudah dibayar datang di
        // "paidAmount", bukan "amount" seperti di payload qr-mpm-query.
        $amountField = $request->input('paidAmount') ?? $request->input('amount');
        $paidAmount = isset($amountField['value']) ? (int) round((float) $amountField['value']) : null;

        if (in_array($status, ['success', 'paid', '00'], true)) {
            if ($paidAmount !== null && ! $branchPayment->receivedAmountMatches($paidAmount)) {
                $branchPayment->update(['status' => 'failed']);
                Log::warning('gdcpay.branch_notification.amount_mismatch', ['reference' => $branchPayment->prefixed_reference_no]);
            } elseif ($branchPayment->status !== 'paid') {
                $branchPayment->update(['status' => 'paid', 'paid_at' => now()]);
            }
        } elseif ($branchPayment->status === 'pending') {
            $branchPayment->update(['status' => 'failed']);
        }

        $branch = $branchPayment->branch;

        try {
            Http::withHeaders(['X-Branch-Key' => $branch->api_key])
                ->timeout(10)
                ->post($branch->callback_url, [
                    'reference_no' => $branchPayment->branch_reference_no,
                    'status' => $branchPayment->status,
                ])
                ->throw();
        } catch (\Throwable $e) {
            Log::warning('gdcpay.branch_forward_failed', [
                'branch' => $branch->code,
                'reference' => $branchPayment->branch_reference_no,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
