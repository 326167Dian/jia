<?php

namespace App\Http\Controllers;

use App\Models\MidtransPayment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(protected MidtransService $midtrans) {}

    public function notification(Request $request)
    {
        $orderId = $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        if (! $this->midtrans->verifyNotificationSignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('midtrans.webhook.invalid_signature', ['order_id' => $orderId]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = MidtransPayment::where('order_id', $orderId)->first();

        if (! $payment) {
            Log::warning('midtrans.webhook.payment_not_found', ['order_id' => $orderId]);

            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $request->input('transaction_status');

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if (! $payment->receivedAmountMatches((float) $grossAmount)) {
                $payment->update(['status' => 'failed']);
                Log::warning('midtrans.webhook.amount_mismatch', ['order_id' => $orderId]);
            } else {
                $payment->markAsPaid();
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'OK']);
    }
}
