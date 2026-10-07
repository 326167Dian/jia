<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'product_name',
        'amount',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'product_name' => 'MySIFA E-Commerce',
            'amount' => 4000000,
        ]);
    }
}
