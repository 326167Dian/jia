<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('reseller')->check()) {
            return redirect()->route('reseller.dashboard');
        }

        return view('reseller.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('reseller')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'Username atau password tidak sesuai.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        $reseller = Auth::guard('reseller')->user();

        if (! $reseller->is_active) {
            return redirect()->route('reseller.pending');
        }

        return redirect()->intended(route('reseller.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('reseller')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('reseller.login');
    }

    public function showRegister()
    {
        if (Auth::guard('reseller')->check()) {
            return redirect()->route('reseller.dashboard');
        }

        return view('reseller.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:60', 'unique:resellers,username', 'alpha_dash'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'bank_name' => ['required', 'string', 'max:60'],
            'bank_account' => ['required', 'string', 'max:50'],
        ]);

        Reseller::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'] ?? null,
            'bank_name' => $data['bank_name'],
            'bank_account' => $data['bank_account'],
            'is_active' => false,
        ]);

        return redirect()
            ->route('reseller.login')
            ->with('status', 'Registrasi berhasil. Akun Anda akan aktif setelah disetujui admin.');
    }

    public function showPending()
    {
        $reseller = Auth::guard('reseller')->user();

        if ($reseller->is_active) {
            return redirect()->route('reseller.dashboard');
        }

        return view('reseller.pending', compact('reseller'));
    }
}
