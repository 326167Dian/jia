<?php

namespace App\Http\Controllers;

use App\Models\QrisPayment;
use Illuminate\Http\Request;
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

        if (! $payment) {
            Log::warning('gdcpay.webhook.payment_not_found', ['reference' => $referenceNo]);

            return response()->json(['responseCode' => '4042500', 'responseMessage' => 'Payment not found'], 404);
        }

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
}
