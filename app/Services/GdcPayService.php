<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Integrasi GDC Pay QRIS Dinamis (standar SNAP).
 *
 * Dokumentasi: https://neo-docs.gdc.co.id
 *
 * Catatan: kelas ini siap pakai begitu kredensial (GDCPAY_*) diisi di .env.
 * Tanpa kredensial, generateQr()/queryQr() akan melempar exception yang
 * ditangkap oleh controller pemanggil sehingga customer diarahkan fallback
 * ke QRIS Statis.
 */
class GdcPayService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('gdcpay.base_url'), '/');
    }

    public function isConfigured(): bool
    {
        return filled(config('gdcpay.client_key'))
            && filled(config('gdcpay.client_secret'))
            && filled(config('gdcpay.partner_id'))
            && filled(config('gdcpay.merchant_id'));
    }

    /**
     * Minta GDC Pay membuatkan QR dinamis untuk nominal tertentu.
     *
     * @return array{reference_no:string,qr_content:string,gdc_reference_no:?string}
     */
    public function generateQr(string $partnerReferenceNo, int $amount): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Kredensial GDC Pay belum diisi.');
        }

        $path = '/snap/v1.0/qr/qr-mpm-generate';
        $timestamp = now()->toIso8601String();

        $body = [
            'partnerReferenceNo' => $partnerReferenceNo,
            'amount' => [
                'value' => number_format($amount, 2, '.', ''),
                'currency' => 'IDR',
            ],
            'merchantId' => config('gdcpay.merchant_id'),
            'additionalInfo' => [
                'accountNumber' => config('gdcpay.account_number'),
            ],
        ];

        $response = $this->post($path, $body, $timestamp);

        return [
            'reference_no' => $partnerReferenceNo,
            'qr_content' => $response['qrContent'] ?? $response['qrUrl'] ?? '',
            'gdc_reference_no' => $response['referenceNo'] ?? null,
        ];
    }

    /**
     * Cek status pembayaran QR yang sudah dibuat.
     *
     * @return array{status:string,paid_amount:?int,description:?string}
     */
    public function queryQr(string $partnerReferenceNo, ?string $gdcReferenceNo = null): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Kredensial GDC Pay belum diisi.');
        }

        $path = '/snap/v1.0/qr/qr-mpm-query';
        $timestamp = now()->toIso8601String();

        $body = array_filter([
            'originalPartnerReferenceNo' => $partnerReferenceNo,
            'originalReferenceNo' => $gdcReferenceNo,
            'merchantId' => config('gdcpay.merchant_id'),
        ]);

        $response = $this->post($path, $body, $timestamp);

        $status = strtolower($response['latestTransactionStatus'] ?? $response['transactionStatusDesc'] ?? 'pending');
        $paidAmount = isset($response['amount']['value']) ? (int) round((float) $response['amount']['value']) : null;

        return [
            'status' => $status,
            'paid_amount' => $paidAmount,
            'description' => $response['transactionStatusDesc'] ?? null,
        ];
    }

    protected function post(string $path, array $body, string $timestamp): array
    {
        $signature = $this->signBody('POST', $path, $body, $timestamp);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'X-PARTNER-ID' => config('gdcpay.partner_id'),
            'X-EXTERNAL-ID' => (string) Str::uuid(),
            'CHANNEL-ID' => config('gdcpay.channel_id'),
            'Client-Key' => config('gdcpay.client_key'),
        ])->post($this->baseUrl.$path, $body);

        if ($response->failed()) {
            Log::warning('gdcpay.request_failed', [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Permintaan ke GDC Pay gagal ('.$response->status().').');
        }

        return $response->json() ?? [];
    }

    protected function signBody(string $method, string $path, array $body, string $timestamp): string
    {
        $minifiedBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $bodyHash = hash('sha256', $minifiedBody);

        $stringToSign = sprintf('%s:%s:%s:%s:%s', $method, $path, '', $bodyHash, $timestamp);

        return base64_encode(hash_hmac('sha512', $stringToSign, config('gdcpay.client_secret'), true));
    }
}
