<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();

        return view('backend.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('backend.vouchers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher baru berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher)
    {
        return view('backend.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $this->validateData($request, $voucher);

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return back()->with('success', 'Voucher berhasil dihapus.');
    }

    private function validateData(Request $request, ?Voucher $voucher = null): array
    {
        $data = $request->validate([
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('vouchers', 'code')->ignore($voucher?->id),
            ],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'integer', 'min:1'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            $data['value'] = 100;
        }

        return $data;
    }
}
