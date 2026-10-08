<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'product_name',
        'amount',
        'monthly_amount',
        'bank_name',
        'bank_account',
        'bank_holder',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'product_name' => 'MySIFA E-Commerce',
            'amount' => 4000000,
            'monthly_amount' => 400000,
            'bank_name' => config('services.promo_bank.name'),
            'bank_account' => config('services.promo_bank.account'),
            'bank_holder' => config('services.promo_bank.holder'),
        ]);
    }

    public function amountFor(string $period): int
    {
        return $period === 'monthly' ? (int) $this->monthly_amount : (int) $this->amount;
    }
}
