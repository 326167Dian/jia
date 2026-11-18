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
        'billing_period',
        'voucher_id',
        'voucher_code',
        'discount_amount',
        'final_amount',
        'proof_path',
        'status',
        'reseller_id',
        'commission_amount',
        'commission_proof_path',
        'commission_paid_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'commission_paid_at' => 'datetime',
        ];
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function pharmacy()
    {
        return $this->hasOne(Pharmacy::class);
    }

    public function billingPeriodLabel(): string
    {
        return $this->billing_period === 'monthly' ? 'Bulanan' : 'Tahunan';
    }

    /**
     * Masa berlaku kontrak dihitung dari tanggal invoice diterbitkan (created_at)
     * ditambah 1 bulan/tahun sesuai paket -- bukan kolom tersendiri, karena aturan
     * defaultnya selalu "sejak invoice terbit", kecuali ada perjanjian tambahan
     * di luar sistem (dicatat manual, bukan lewat form ini).
     */
    public function contractEndsAt(): \Carbon\Carbon
    {
        return $this->billing_period === 'monthly'
            ? $this->created_at->copy()->addMonth()
            : $this->created_at->copy()->addYear();
    }

    public function contractPeriodLabel(): string
    {
        return $this->created_at->format('d/m/Y').' s.d. '.$this->contractEndsAt()->format('d/m/Y');
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
