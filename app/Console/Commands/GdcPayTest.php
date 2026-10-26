<?php

namespace App\Console\Commands;

use App\Services\GdcPayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Uji koneksi GDC Pay langsung dari terminal:
 *   php artisan gdcpay:test
 *   php artisan gdcpay:test 10000
 */
class GdcPayTest extends Command
{
    protected $signature = 'gdcpay:test {amount=10000 : Nominal uji (Rupiah)}';

    protected $description = 'Uji ambil Access Token dan generate QR GDC Pay, lalu tampilkan hasil / pesan error lengkap';

    public function handle(GdcPayService $gdcPay): int
    {
        $this->line('Base URL     : '.config('gdcpay.base_url'));
        $this->line('Private key  : '.config('gdcpay.private_key_path').(is_file((string) config('gdcpay.private_key_path')) ? '  (ADA)' : '  (TIDAK DITEMUKAN)'));
        $ck = (string) config('gdcpay.client_key');
        $this->line('Client Key   : '.substr($ck, 0, 8).'… (panjang '.strlen($ck).' karakter'.($ck !== trim($ck) ? ', ADA SPASI di awal/akhir!' : '').')');
        $this->line('Kredensial   : '.($gdcPay->isConfigured() ? 'lengkap' : 'BELUM LENGKAP (cek GDCPAY_CLIENT_KEY/SECRET/PARTNER_ID/MERCHANT_ID)'));
        $this->newLine();

        // Mulai dari token baru agar hasil uji akurat.
        Cache::forget('gdcpay.access_token');

        try {
            $result = $gdcPay->generateQr('TEST-'.Str::upper(Str::random(10)), (int) $this->argument('amount'));
        } catch (\Throwable $e) {
            $this->error('GAGAL: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('BERHASIL — QR dibuat.');
        $this->line('Isi QR (qrContent): '.Str::limit($result['qr_content'], 120));
        $this->line('Reference GDC     : '.($result['gdc_reference_no'] ?? '-'));

        return self::SUCCESS;
    }
}
