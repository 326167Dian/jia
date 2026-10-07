<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'promo_order_id',
        'nama_apotek',
        'nomor_izin_apotek',
        'alamat_apotek',
        'telp_apotek',
        'nama_apoteker',
        'nomor_sipa',
        'alamat_apoteker',
        'telp_apoteker',
        'nama_pemilik',
        'telp_pemilik',
        'alamat_pemilik',
        'logo_path',
    ];

    public function promoOrder()
    {
        return $this->belongsTo(PromoOrder::class);
    }
}
