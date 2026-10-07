<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'is_active',
        'quota',
        'used_count',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $amount): int
    {
        $discount = $this->type === 'percent'
            ? intdiv($amount * $this->value, 100)
            : $this->value;

        return min($discount, $amount);
    }
}
