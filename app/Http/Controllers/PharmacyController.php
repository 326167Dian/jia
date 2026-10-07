<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\PromoOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PharmacyController extends Controller
{
    public function edit(string $orderCode)
    {
        $order = PromoOrder::with('pharmacy')->where('order_code', $orderCode)->firstOrFail();

        if ($order->status !== 'verified') {
            return view('pharmacy.not-ready', compact('order'));
        }

        $pharmacy = $order->pharmacy ?? new Pharmacy();

        return view('pharmacy.edit', compact('order', 'pharmacy'));
    }

    public function update(Request $request, string $orderCode)
    {
        $order = PromoOrder::with('pharmacy')->where('order_code', $orderCode)->firstOrFail();

        if ($order->status !== 'verified') {
            abort(403, 'Pembayaran belum diverifikasi.');
        }

        $data = $request->validate([
            'nama_apotek' => ['required', 'string', 'max:150'],
            'nomor_izin_apotek' => ['nullable', 'string', 'max:100'],
            'alamat_apotek' => ['nullable', 'string', 'max:500'],
            'telp_apotek' => ['nullable', 'string', 'max:30'],
            'nama_apoteker' => ['nullable', 'string', 'max:150'],
            'nomor_sipa' => ['nullable', 'string', 'max:100'],
            'alamat_apoteker' => ['nullable', 'string', 'max:500'],
            'telp_apoteker' => ['nullable', 'string', 'max:30'],
            'nama_pemilik' => ['required', 'string', 'max:150'],
            'telp_pemilik' => ['required', 'string', 'max:30'],
            'alamat_pemilik' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $pharmacy = $order->pharmacy ?? new Pharmacy(['promo_order_id' => $order->id]);

        $pharmacy->fill($data);

        if ($request->hasFile('logo')) {
            $pharmacy->logo_path = $request->file('logo')->store('logo-apotek', 'public');
        }

        $pharmacy->save();

        if (! empty($data['password'])) {
            $order->password = Hash::make($data['password']);
            $order->save();
        }

        // Login otomatis ke akun member supaya langsung diarahkan ke halaman pelanggan.
        if ($order->password) {
            Auth::guard('member')->login($order);

            return redirect()
                ->route('member.dashboard')
                ->with('success', 'Data apotek berhasil disimpan.');
        }

        return redirect()
            ->route('pharmacy.edit', $order->order_code)
            ->with('success', 'Data apotek berhasil disimpan.');
    }
}
