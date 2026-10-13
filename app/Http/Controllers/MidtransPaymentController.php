<?php

namespace App\Http\Controllers;

use App\Models\MidtransPayment;
use App\Models\Reseller;
use App\Services\MidtransService;
use App\Support\PromoPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MidtransPaymentController extends Controller
{
    public function __construct(protected MidtransService $midtrans) {}

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'billing_period' => ['required', 'in:monthly,yearly'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'referral_code' => ['nullable', 'integer'],
        ]);

        $pricing = PromoPricing::calculate($data['billing_period'], $data['voucher_code'] ?? null);

        $reseller = ! empty($data['referral_code'])
            ? Reseller::where('id', $data['referral_code'])->where('is_active', true)->first()
            : null;

        $orderId = 'mysifa-promo-'.now()->format('YmdHis').'-'.strtoupper(Str::random(6));

        $payment = MidtransPayment::create([
            'order_id' => $orderId,
            'pending_name' => $data['name'],
            'pending_phone' => $data['phone'],
            'pending_email' => $data['email'] ?? null,
            'billing_period' => $data['billing_period'],
            'voucher_code' => $pricing['voucher']?->code,
            'reseller_id' => $reseller?->id,
            'original_amount' => $pricing['original'],
            'discount_amount' => $pricing['discount'],
            'amount' => $pricing['final'],
            'status' => 'pending',
            'expires_at' => now()->addMinutes(config('midtrans.qr_expiry_minutes', 15)),
        ]);

        try {
            $result = $this->midtrans->createQrisCharge($orderId, $pricing['final']);

            $payment->update([
                'qr_string' => $result['qr_string'],
                'qr_image_url' => $result['qr_image_url'],
                'midtrans_transaction_id' => $result['transaction_id'],
            ]);
        } catch (\Throwable $e) {
            $payment->delete();

            return back()->with('error', 'Pembayaran QRIS otomatis sedang tidak tersedia. Silakan gunakan QRIS Statis / transfer manual.');
        }

        return redirect()->route('promo.midtrans.show', $payment->order_id);
    }

    public function show(string $orderId)
    {
        $payment = MidtransPayment::where('order_id', $orderId)->firstOrFail();

        return view('promo.midtrans-show', compact('payment'));
    }

    public function status(string $orderId)
    {
        $payment = MidtransPayment::where('order_id', $orderId)->firstOrFail();

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'paid', 'order_code' => $payment->promoOrder?->order_code]);
        }

        if ($payment->isExpired()) {
            $payment->update(['status' => 'expired']);

            return response()->json(['status' => 'expired']);
        }

        if (! $payment->last_checked_at || $payment->last_checked_at->diffInSeconds(now()) >= 3) {
            try {
                $result = $this->midtrans->getStatus($payment->order_id);
                $payment->update(['last_checked_at' => now()]);

                if (in_array($result['status'], ['settlement', 'capture'], true)) {
                    if ($result['gross_amount'] === null || $payment->receivedAmountMatches($result['gross_amount'])) {
                        $order = $payment->markAsPaid();

                        return response()->json(['status' => 'paid', 'order_code' => $order->order_code]);
                    }

                    $payment->update(['status' => 'failed']);

                    return response()->json(['status' => 'failed', 'message' => 'Nominal pembayaran tidak sesuai.']);
                }

                if (in_array($result['status'], ['deny', 'cancel', 'expire', 'failure'], true)) {
                    $payment->update(['status' => 'failed']);

                    return response()->json(['status' => 'failed']);
                }
            } catch (\Throwable $e) {
                // Biarkan status tetap pending, polling berikutnya akan coba lagi.
            }
        }

        return response()->json(['status' => $payment->status]);
    }
}
