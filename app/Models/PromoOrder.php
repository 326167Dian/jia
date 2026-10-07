<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PromoOrder extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'order_code',
        'name',
        'phone',
        'email',
        'password',
        'amount',
        'voucher_id',
        'voucher_code',
        'discount_amount',
        'final_amount',
        'proof_path',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function pharmacy()
    {
        return $this->hasOne(Pharmacy::class);
    }

    public function isMemberAccountActive(): bool
    {
        return $this->status === 'verified' && ! empty($this->password);
    }

    public function whatsappPhone(): string
    {
        $digits = preg_replace('/\D/', '', $this->phone);

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return '62'.$digits;
    }
}
