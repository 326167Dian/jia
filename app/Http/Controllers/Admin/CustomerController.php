<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = PromoOrder::with('pharmacy')->latest()->get();

        return view('backend.customers.index', compact('customers'));
    }

    /**
     * Form tambah customer manual -- untuk pelanggan yang deal/bayar di luar
     * form promo publik (mis. harga khusus teman, transfer langsung ke admin)
     * sehingga tidak pernah mengisi form pendaftaran + upload bukti transfer.
     */
    public function create()
    {
        return view('backend.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'billing_period' => ['required', 'in:monthly,yearly'],
            'final_amount' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:pending,verified,rejected'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $order = PromoOrder::create([
            'order_code' => 'MYS-'.strtoupper(Str::random(8)),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'amount' => $data['final_amount'],
            'billing_period' => $data['billing_period'],
            'discount_amount' => 0,
            'final_amount' => $data['final_amount'],
            'status' => $data['status'],
            'password' => $data['password'] ?? null,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', "Customer \"{$order->name}\" ({$order->order_code}) berhasil ditambahkan secara manual.");
    }

    public function edit(PromoOrder $customer)
    {
        return view('backend.customers.edit', ['customer' => $customer]);
    }

    public function update(Request $request, PromoOrder $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'billing_period' => ['required', 'in:monthly,yearly'],
            'final_amount' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:pending,verified,rejected'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->fill([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'amount' => $data['final_amount'],
            'final_amount' => $data['final_amount'],
            'billing_period' => $data['billing_period'],
            'status' => $data['status'],
        ]);

        if (! empty($data['password'])) {
            $customer->password = $data['password'];
        }

        $customer->save();

        return redirect()->route('admin.customers.index')
            ->with('success', "Data customer \"{$customer->name}\" berhasil diperbarui.");
    }
}
