<?php

namespace App\Http\Controllers;

use App\Models\QrisPayment;
use App\Models\Reseller;
use App\Services\GdcPayService;
use App\Support\PromoPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrisPaymentController extends Controller
{
    public function __construct(protected GdcPayService $gdcPay) {}

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

        $referenceNo = (string) Str::uuid();

        $payment = QrisPayment::create([
            'reference_no' => $referenceNo,
            'external_id' => (string) Str::uuid(),
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
            'expires_at' => now()->addMinutes(config('gdcpay.qr_expiry_minutes', 15)),
        ]);

        try {
            $result = $this->gdcPay->generateQr($referenceNo, $pricing['final']);

            $payment->update([
                'qr_content' => $result['qr_content'],
                'gdc_reference_no' => $result['gdc_reference_no'],
            ]);
        } catch (\Throwable $e) {
            $payment->delete();

            return back()->with('error', 'Pembayaran QRIS otomatis sedang tidak tersedia. Silakan gunakan QRIS Statis / transfer manual.');
        }

        return redirect()->route('promo.qris.show', $payment->reference_no);
    }

    public function show(string $reference)
    {
        $payment = QrisPayment::where('reference_no', $reference)->firstOrFail();

        return view('promo.qris-show', compact('payment'));
    }

    public function status(string $reference)
    {
        $payment = QrisPayment::where('reference_no', $reference)->firstOrFail();

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'paid', 'order_code' => $payment->promoOrder?->order_code]);
        }

        if ($payment->isExpired()) {
            $payment->update(['status' => 'expired']);

            return response()->json(['status' => 'expired']);
        }

        // Throttle upstream polling to at most once per 3 seconds per payment.
        if (! $payment->last_checked_at || $payment->last_checked_at->diffInSeconds(now()) >= 3) {
            try {
                $result = $this->gdcPay->queryQr($payment->reference_no, $payment->gdc_reference_no);
                $payment->update(['last_checked_at' => now()]);

                if (in_array($result['status'], ['success', 'paid', '00'], true)) {
                    if ($result['paid_amount'] === null || $payment->receivedAmountMatches($result['paid_amount'])) {
                        $order = $payment->markAsPaid();

                        return response()->json(['status' => 'paid', 'order_code' => $order->order_code]);
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
