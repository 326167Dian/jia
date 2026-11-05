<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchQrisPayment;
use App\Services\GdcPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * API untuk cabang (mis. apotekberlian.my.id) membuat & mengecek QRIS GDC
 * lewat hub ini, bukan langsung ke GDC -- GDC cuma mengizinkan SATU url
 * callback per partner, jadi hub (akun GDC "PT Mysifa Digital Network") yang
 * jadi satu-satunya partner terdaftar; tiap cabang didemuks lewat prefix kode
 * cabang di partnerReferenceNo (lihat GdcPayWebhookController::qrisNotification()).
 *
 * Diautentikasi middleware 'branch.auth' (header X-Branch-Key), yang
 * menaruh model Branch yang cocok di $request->attributes->get('branch').
 */
class BranchQrisController extends Controller
{
    public function __construct(protected GdcPayService $gdcPay) {}

    public function generate(Request $request)
    {
        /** @var Branch $branch */
        $branch = $request->attributes->get('branch');

        $data = $request->validate([
            'branch_reference_no' => ['required', 'uuid'],
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        // GDC menolak nominal di bawah ini dengan 500 Internal Server Error (bukan
        // pesan validasi yang jelas) -- ditemukan empiris lewat QrisPaymentController
        // milik hub sendiri. Dicegat di sini supaya SEMUA cabang dapat pesan yang jelas.
        $minAmount = (int) config('gdcpay.min_amount', 10000);
        if ($data['amount'] < $minAmount) {
            return response()->json(['message' => 'Nominal di bawah minimum QRIS GDC Pay (Rp'.number_format($minAmount, 0, ',', '.').').'], 422);
        }

        if (BranchQrisPayment::where('branch_id', $branch->id)
            ->where('branch_reference_no', $data['branch_reference_no'])
            ->exists()) {
            return response()->json(['message' => 'branch_reference_no sudah dipakai.'], 422);
        }

        $prefixedReferenceNo = $branch->code.'-'.$data['branch_reference_no'];

        $payment = BranchQrisPayment::create([
            'branch_id' => $branch->id,
            'branch_reference_no' => $data['branch_reference_no'],
            'prefixed_reference_no' => $prefixedReferenceNo,
            'amount' => $data['amount'],
            'status' => 'pending',
            'expires_at' => now()->addMinutes((int) config('gdcpay.qr_expiry_minutes', 15)),
        ]);

        try {
            $result = $this->gdcPay->generateQr($prefixedReferenceNo, $data['amount']);
        } catch (\Throwable $e) {
            $payment->delete();

            return response()->json(['message' => 'Gagal membuat QR GDC Pay: '.$e->getMessage()], 502);
        }

        $payment->update([
            'qr_content' => $result['qr_content'],
            'gdc_reference_no' => $result['gdc_reference_no'],
            'external_id' => $result['external_id'],
        ]);

        return response()->json([
            'reference_no' => $payment->branch_reference_no,
            'qr_content' => $payment->qr_content,
            'expires_at' => $payment->expires_at->toIso8601String(),
        ]);
    }

    public function status(Request $request, string $referenceNo)
    {
        /** @var Branch $branch */
        $branch = $request->attributes->get('branch');

        $payment = BranchQrisPayment::where('branch_id', $branch->id)
            ->where('branch_reference_no', $referenceNo)
            ->firstOrFail();

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'paid']);
        }

        if ($payment->isExpired()) {
            $payment->update(['status' => 'expired']);

            return response()->json(['status' => 'expired']);
        }

        // Throttle polling ke GDC, sama seperti QrisPaymentController milik hub sendiri.
        if (! $payment->last_checked_at || $payment->last_checked_at->diffInSeconds(now()) >= 3) {
            try {
                $result = $this->gdcPay->queryQr($payment->prefixed_reference_no, $payment->gdc_reference_no, $payment->external_id);
                $payment->update(['last_checked_at' => now()]);

                if (in_array($result['status'], ['success', 'paid', '00'], true)) {
                    if ($result['paid_amount'] === null || $payment->receivedAmountMatches($result['paid_amount'])) {
                        $payment->update(['status' => 'paid', 'paid_at' => now()]);

                        return response()->json(['status' => 'paid']);
                    }

                    $payment->update(['status' => 'failed']);

                    return response()->json(['status' => 'failed', 'message' => 'Nominal pembayaran tidak sesuai.']);
                }
            } catch (\Throwable $e) {
                // Biarkan status tetap pending, polling berikutnya akan coba lagi.
            }
        }

        return response()->json(['status' => $payment->status]);
    }
}
