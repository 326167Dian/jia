<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

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
        ]);

        $setting = PaymentSetting::current();
        $setting->update($data);

        return back()->with('success', 'Pengaturan harga berhasil disimpan.');
    }
}
