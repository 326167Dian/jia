<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\PromoOrder;

class InvoiceController extends Controller
{
    public function show(PromoOrder $order)
    {
        $order->load('pharmacy');

        if (! $order->pharmacy) {
            return back()->with('error', 'Data apotek belum diisi customer, invoice belum bisa dicetak.');
        }

        return view('backend.invoice.show', [
            'order' => $order,
            'pharmacy' => $order->pharmacy,
            'setting' => PaymentSetting::current(),
        ]);
    }
}
