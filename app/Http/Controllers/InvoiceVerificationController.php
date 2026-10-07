<?php

namespace App\Http\Controllers;

use App\Models\PromoOrder;

class InvoiceVerificationController extends Controller
{
    public function show(string $orderCode)
    {
        $order = PromoOrder::with('pharmacy')
            ->where('order_code', $orderCode)
            ->where('status', 'verified')
            ->whereHas('pharmacy')
            ->first();

        return view('invoice.verify', compact('order'));
    }
}
