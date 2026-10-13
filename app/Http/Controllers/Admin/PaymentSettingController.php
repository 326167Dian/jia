<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function edit()
    {
        $setting = PaymentSetting::current();

        return view('backend.payment.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'integer', 'min:1000'],
            'monthly_amount' => ['required', 'integer', 'min:1000'],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account' => ['required', 'string', 'max:50'],
            'bank_holder' => ['required', 'string', 'max:150'],
            'payment_method' => ['required', 'in:qris_static,gdcpay,midtrans'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_qris_image' => ['nullable', 'boolean'],
        ]);

        $setting = PaymentSetting::current();

        if ($data['payment_method'] !== 'qris_static' && ! $setting->isGatewayConfigured($data['payment_method'])) {
            return back()->with('error', 'Kredensial untuk metode ini belum diisi di .env. Isi dulu kredensialnya sebelum mengaktifkan.');
        }

        $setting->fill($data);

        if ($request->hasFile('qris_image')) {
            if ($setting->qris_image_path) {
                Storage::disk('uploads')->delete($setting->qris_image_path);
            }

            $setting->qris_image_path = $request->file('qris_image')->store('qris-statis', 'uploads');
        } elseif ($request->boolean('remove_qris_image') && $setting->qris_image_path) {
            Storage::disk('uploads')->delete($setting->qris_image_path);
            $setting->qris_image_path = null;
        }

        $setting->save();

        return back()->with('success', 'Pengaturan pembayaran berhasil disimpan.');
    }
}
