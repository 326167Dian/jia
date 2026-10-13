<?php

namespace App\Support;

use App\Models\PaymentSetting;
use App\Models\Voucher;

class PromoPricing
{
    /**
     * @return array{original: int, discount: int, final: int, voucher: ?Voucher}
     */
    public static function calculate(string $billingPeriod, ?string $voucherCode): array
    {
        $original = PaymentSetting::current()->amountFor($billingPeriod);
        $discount = 0;
        $voucher = null;

        if ($billingPeriod === 'yearly' && filled($voucherCode)) {
            $candidate = Voucher::whereRaw('LOWER(code) = ?', [strtolower($voucherCode)])->first();

            if ($candidate && $candidate->isValid()) {
                $discount = $candidate->calculateDiscount($original);
                $voucher = $candidate;
            }
        }

        return [
            'original' => $original,
            'discount' => $discount,
            'final' => $original - $discount,
            'voucher' => $voucher,
        ];
    }
}
