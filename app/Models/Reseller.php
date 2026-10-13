<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reseller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'name',
        'phone',
        'address',
        'bank_name',
        'bank_account',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(PromoOrder::class);
    }

    public function referralUrl(): string
    {
        return route('reseller.referral', $this->id);
    }
}
