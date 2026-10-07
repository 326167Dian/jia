<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PromoOrder;
use App\Models\Voucher;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_admin' => Admin::count(),
            'total_voucher' => Voucher::count(),
            'total_pendaftar' => PromoOrder::count(),
            'menunggu_verifikasi' => PromoOrder::where('status', 'pending')->count(),
        ];

        $latestOrders = PromoOrder::latest()->take(5)->get();

        return view('backend.dashboard', compact('stats', 'latestOrders'));
    }
}
