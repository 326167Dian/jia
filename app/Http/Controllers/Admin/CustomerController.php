<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoOrder;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = PromoOrder::with('pharmacy')->latest()->get();

        return view('backend.customers.index', compact('customers'));
    }
}
