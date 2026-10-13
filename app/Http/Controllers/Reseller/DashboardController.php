<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $reseller = Auth::guard('reseller')->user();

        if (! $reseller->is_active) {
            return redirect()->route('reseller.pending');
        }

        $orders = $reseller->orders()->latest()->get();

        return view('reseller.dashboard', compact('reseller', 'orders'));
    }
}
