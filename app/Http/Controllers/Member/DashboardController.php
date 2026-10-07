<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $order = Auth::guard('member')->user()->load('pharmacy');

        return view('member.dashboard', compact('order'));
    }
}
