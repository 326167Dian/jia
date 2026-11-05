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
        $key = (string) $request->header('X-Branch-Key', '');

        abort_if($key === '', 401, 'X-Branch-Key wajib diisi.');

        $branch = Branch::where('is_active', true)->where('api_key', $key)->first();

        abort_unless($branch, 401, 'X-Branch-Key tidak valid.');

        $request->attributes->set('branch', $branch);

        return $next($request);
    }
}
