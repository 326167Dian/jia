<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoOrder;
use App\Models\Reseller;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    public function index()
    {
        $resellers = Reseller::withCount('orders')->latest()->get();

        return view('backend.resellers.index', compact('resellers'));
    }

    public function approve(Reseller $reseller)
    {
        $reseller->update(['is_active' => true]);

        return back()->with('success', "Reseller {$reseller->name} berhasil disetujui.");
    }

    public function reject(Reseller $reseller)
    {
        $reseller->update(['is_active' => false]);

        return back()->with('success', "Reseller {$reseller->name} dinonaktifkan.");
    }

    public function show(Reseller $reseller)
    {
        $orders = $reseller->orders()->latest()->get();

        return view('backend.resellers.show', compact('reseller', 'orders'));
    }

    public function commission(Request $request, Reseller $reseller, PromoOrder $order)
    {
        abort_unless((int) $order->reseller_id === (int) $reseller->id, 404);

        $data = $request->validate([
            'commission_amount' => ['required', 'integer', 'min:0'],
            'commission_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $order->commission_amount = $data['commission_amount'];
        $order->commission_proof_path = $request->file('commission_proof')->store('komisi-reseller', 'uploads');
        $order->commission_paid_at = now();
        $order->save();

        return back()->with('success', 'Bukti komisi berhasil diupload.');
    }
}
