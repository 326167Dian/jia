<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * CRUD cabang QRIS GDC Pay (lihat BranchQrisController/GdcPayWebhookController) --
 * sebelum ini, mendaftarkan cabang baru (mis. apotek lain) harus lewat tinker manual
 * di server. api_key dibuat otomatis di sini (bukan diisi admin) karena harus berupa
 * shared secret acak yang sama persis dengan yang nanti ditaruh di .env cabang.
 */
class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->get();

        return view('backend.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('backend.branches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'alpha_dash', Rule::unique('branches', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'callback_url' => ['required', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['api_key'] = bin2hex(random_bytes(32));

        $branch = Branch::create($data);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang "' . $branch->name . '" berhasil ditambahkan. Salin API Key di bawah ini ke file .env cabang (HUBQRIS_API_KEY) -- key ini hanya ditampilkan penuh sekali.')
            ->with('reveal_key_for', $branch->id);
    }

    public function edit(Branch $branch)
    {
        return view('backend.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        // 'code' sengaja tidak bisa diubah lewat form ini -- sudah terpakai sebagai
        // prefix partnerReferenceNo pada pembayaran-pembayaran lama cabang ini
        // (lihat BranchQrisController::generate()); mengubahnya akan membuat webhook
        // GDC untuk transaksi lama tidak lagi bisa didemuks ke cabang yang benar.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'callback_url' => ['required', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $branch->update($data);

        return redirect()->route('admin.branches.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return back()->with('success', 'Cabang berhasil dihapus.');
    }

    /**
     * API Key lama langsung tidak berlaku lagi -- cabang HARUS diperbarui dengan key
     * baru di .env-nya (HUBQRIS_API_KEY) sebelum bisa generate/cek QRIS lagi.
     */
    public function regenerateKey(Branch $branch)
    {
        $branch->update(['api_key' => bin2hex(random_bytes(32))]);

        return redirect()->route('admin.branches.index')
            ->with('success', 'API Key cabang "' . $branch->name . '" berhasil dibuat ulang. Perbarui HUBQRIS_API_KEY di .env cabang SEKARANG, key lama sudah tidak berlaku.')
            ->with('reveal_key_for', $branch->id);
    }
}
