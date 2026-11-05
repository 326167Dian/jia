<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchQrisPayment extends Model
{
    protected $fillable = [
        'branch_id',
        'branch_reference_no',
        'prefixed_reference_no',
        'external_id',
        'gdc_reference_no',
        'amount',
        'qr_content',
        'status',
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->expires_at->isPast();
    }

    public function receivedAmountMatches(int $receivedAmount): bool
    {
        return $receivedAmount === (int) $this->amount;
    }
}
