<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Integrasi Midtrans Core API untuk QRIS.
 *
 * Dokumentasi: https://docs.midtrans.com/reference/qris-apis
 *
 * Kelas ini siap pakai begitu kredensial (MIDTRANS_*) diisi di .env.
 */
class MidtransService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    public function isConfigured(): bool
    {
        return filled(config('midtrans.server_key'));
    }

    /**
     * Buat charge QRIS baru.
     *
     * @return array{qr_string:?string,qr_image_url:?string,transaction_id:?string}
     */
    public function createQrisCharge(string $orderId, int $amount): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Kredensial Midtrans belum diisi.');
        }

        $response = $this->client()->post($this->baseUrl.'/v2/charge', [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
            'custom_expiry' => [
                'expiry_duration' => config('midtrans.qr_expiry_minutes', 15),
                'unit' => 'minute',
            ],
        ]);

        if ($response->failed()) {
            Log::warning('midtrans.charge_failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Permintaan ke Midtrans gagal ('.$response->status().').');
        }

        $data = $response->json();

        $qrAction = collect($data['actions'] ?? [])->firstWhere('name', 'generate-qr-code');

        return [
            'qr_string' => $data['qr_string'] ?? null,
            'qr_image_url' => $qrAction['url'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
        ];
    }

    /**
     * Cek status transaksi.
     *
     * @return array{status:string,gross_amount:?float,transaction_id:?string}
     */
    public function getStatus(string $orderId): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Kredensial Midtrans belum diisi.');
        }

        $response = $this->client()->get($this->baseUrl.'/v2/'.$orderId.'/status');

        if ($response->failed()) {
            throw new \RuntimeException('Gagal mengambil status transaksi Midtrans ('.$response->status().').');
        }

        $data = $response->json();

        return [
            'status' => $data['transaction_status'] ?? 'pending',
            'gross_amount' => isset($data['gross_amount']) ? (float) $data['gross_amount'] : null,
            'transaction_id' => $data['transaction_id'] ?? null,
        ];
    }

    public function verifyNotificationSignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.config('midtrans.server_key'));

        return hash_equals($expected, $signatureKey);
    }

    protected function client()
    {
        return Http::withBasicAuth(config('midtrans.server_key'), '')
            ->withHeaders(['Accept' => 'application/json']);
    }
}
