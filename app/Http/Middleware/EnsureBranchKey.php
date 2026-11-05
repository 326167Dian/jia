<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBranchKey
{
    /**
     * Cocokkan header X-Branch-Key terhadap branches.api_key. Disimpan plaintext
     * dengan sengaja (shared secret, bukan password) -- hub perlu mengirimkannya
     * balik ke cabang saat meneruskan notifikasi pembayaran, jadi tidak bisa di-hash
     * satu arah seperti password. Lihat catatan di migration create_branches_table.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Endpoint ini murni API server-to-server -- paksa respons JSON (termasuk
        // pesan error validasi) apa pun Accept header si pemanggil, supaya tidak
        // jatuh ke perilaku default Laravel untuk request non-JSON (redirect
        // back ke halaman sebelumnya saat validasi gagal).
        $request->headers->set('Accept', 'application/json');

        $key = (string) $request->header('X-Branch-Key', '');

        abort_if($key === '', 401, 'X-Branch-Key wajib diisi.');

        $branch = Branch::where('is_active', true)->where('api_key', $key)->first();

        abort_unless($branch, 401, 'X-Branch-Key tidak valid.');

        $request->attributes->set('branch', $branch);

        return $next($request);
    }
}
