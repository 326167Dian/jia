<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-mysifa');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/mobile', function () {
    return view('mobile');
});

Route::get('/landing-mysifa', function () {
    return view('landing-mysifa');
});

Route::post('/mobile/contact', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'string', 'max:30'],
        'message' => ['required', 'string', 'max:500'],
    ]);

    return back()->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
});
