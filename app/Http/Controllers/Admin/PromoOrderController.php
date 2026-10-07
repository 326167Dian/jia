<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoOrder;
use Illuminate\Http\Request;

class PromoOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = PromoOrder::with('pharmacy')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        return view('backend.orders.index', compact('orders'));
    }

    public function verify(PromoOrder $order)
    {
        $order->update(['status' => 'verified']);

        return back()->with('success', "Pendaftaran {$order->order_code} berhasil diverifikasi.");
    }

    public function reject(PromoOrder $order)
    {
        $order->update(['status' => 'rejected']);

        return back()->with('success', "Pendaftaran {$order->order_code} ditolak.");
    }
}
