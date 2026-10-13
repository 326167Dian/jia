<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MidtransPayment extends Model
{
    protected $fillable = [
        'order_id',
        'midtrans_transaction_id',
        'pending_name',
        'pending_phone',
        'pending_email',
        'billing_period',
        'voucher_code',
        'reseller_id',
        'original_amount',
        'discount_amount',
        'amount',
        'qr_string',
        'qr_image_url',
        'status',
        'promo_order_id',
        'paid_at',
        'expires_at',
        'last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_checked_at' => 'datetime',
        ];
    }

    public function promoOrder()
    {
        return $this->belongsTo(PromoOrder::class);
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->expires_at->isPast();
    }

    public function receivedAmountMatches(float $receivedAmount): bool
    {
        return (int) round($receivedAmount) === (int) $this->amount;
    }

    public function markAsPaid(): PromoOrder
    {
        if ($this->status === 'paid' && $this->promo_order_id) {
            return $this->promoOrder;
        }

        return DB::transaction(function () {
            $this->refresh();

            if ($this->status === 'paid' && $this->promo_order_id) {
                return $this->promoOrder;
            }

            $voucher = $this->voucher_code
                ? Voucher::whereRaw('LOWER(code) = ?', [strtolower($this->voucher_code)])->first()
                : null;

            $voucher?->increment('used_count');

            $order = PromoOrder::create([
                'order_code' => 'MYS-'.strtoupper(Str::random(8)),
                'name' => $this->pending_name,
                'phone' => $this->pending_phone,
                'email' => $this->pending_email,
                'amount' => $this->original_amount,
                'billing_period' => $this->billing_period,
                'voucher_id' => $voucher?->id,
                'voucher_code' => $voucher?->code,
                'discount_amount' => $this->discount_amount,
                'final_amount' => $this->amount,
                'proof_path' => null,
                'status' => 'verified',
                'reseller_id' => $this->reseller_id,
            ]);

            $this->update([
                'status' => 'paid',
                'paid_at' => now(),
                'promo_order_id' => $order->id,
            ]);

            return $order;
        });
    }
}
