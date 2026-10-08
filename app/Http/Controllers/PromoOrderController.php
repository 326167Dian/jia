<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\PromoOrder;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoOrderController extends Controller
{
    public function create()
    {
        $setting = PaymentSetting::current();

        return view('promo.create', [
            'amount' => $setting->amount,
            'monthlyAmount' => $setting->monthly_amount,
            'setting' => $setting,
        ]);
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'period' => ['nullable', 'in:monthly,yearly'],
        ]);

        $period = $request->input('period', 'yearly');

        if ($period === 'monthly') {
            return response()->json(['valid' => false, 'message' => 'Kode voucher hanya berlaku untuk paket Tahunan.']);
        }

        $amount = PaymentSetting::current()->amountFor($period);
        $voucher = Voucher::whereRaw('LOWER(code) = ?', [strtolower($request->code)])->first();

        if (! $voucher || ! $voucher->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid atau sudah tidak berlaku.']);
        }

        $discount = $voucher->calculateDiscount($amount);
        $final = $amount - $discount;

        return response()->json([
            'valid' => true,
            'message' => 'Voucher berhasil diterapkan.',
            'discount' => $discount,
            'final_amount' => $final,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'billing_period' => ['required', 'in:monthly,yearly'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $period = $data['billing_period'];
        $amount = PaymentSetting::current()->amountFor($period);
        $discount = 0;
        $voucher = null;

        if ($period === 'yearly' && ! empty($data['voucher_code'])) {
            $voucher = Voucher::whereRaw('LOWER(code) = ?', [strtolower($data['voucher_code'])])->first();

            if ($voucher && $voucher->isValid()) {
                $discount = $voucher->calculateDiscount($amount);
                $voucher->increment('used_count');
            } else {
                $voucher = null;
            }
        }

        $proofPath = $request->file('proof')->store('bukti-transfer', 'uploads');

        $order = PromoOrder::create([
            'order_code' => 'MYS-'.strtoupper(Str::random(8)),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'amount' => $amount,
            'billing_period' => $period,
            'voucher_id' => $voucher?->id,
            'voucher_code' => $voucher?->code,
            'discount_amount' => $discount,
            'final_amount' => $amount - $discount,
            'proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()->route('promo.success', $order->order_code);
    }

    public function success(string $orderCode)
    {
        $order = PromoOrder::where('order_code', $orderCode)->firstOrFail();

        return view('promo.success', compact('order'));
    }
}
