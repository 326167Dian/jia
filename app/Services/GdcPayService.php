<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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

        $path = '/v1.0/qr/qr-mpm-generate';

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

        ['response' => $response, 'external_id' => $externalId] = $this->postWithRetry($path, $body);

        $qrContent = $response['qrContent'] ?? $response['qrUrl'] ?? $response['additionalInfo']['qrContent'] ?? '';

        if ($qrContent === '') {
            // GDC menjawab sukses tapi tanpa isi QR — tampilkan jawabannya agar mudah dicek.
            Log::warning('gdcpay.generate_qr_empty', ['response' => $response]);

            throw new \RuntimeException('GDC Pay tidak mengirim isi QR. Jawaban GDC: '.json_encode($response, JSON_UNESCAPED_SLASHES));
        }

        return [
            'reference_no' => $partnerReferenceNo,
            'qr_content' => $qrContent,
            'gdc_reference_no' => $response['referenceNo'] ?? null,
            // Disimpan supaya queryQr() berikutnya bisa mengirim originalExternalId yang sama.
            'external_id' => $externalId,
        ];
    }

    /**
     * Cek status pembayaran QR yang sudah dibuat.
     *
     * @return array{status:string,paid_amount:?int,description:?string}
     */
    public function queryQr(string $partnerReferenceNo, ?string $gdcReferenceNo, string $originalExternalId): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Kredensial GDC Pay belum diisi.');
        }

        $path = '/v1.0/qr/qr-mpm-query';

        $body = array_filter([
            'originalPartnerReferenceNo' => $partnerReferenceNo,
            'originalReferenceNo' => $gdcReferenceNo,
            'originalExternalId' => $originalExternalId,
            'serviceCode' => '47',
        ]);

        ['response' => $response] = $this->postWithRetry($path, $body);

        $status = strtolower($response['latestTransactionStatus'] ?? $response['transactionStatusDesc'] ?? 'pending');
        $paidAmount = isset($response['amount']['value']) ? (int) round((float) $response['amount']['value']) : null;

        return [
            'status' => $status,
            'paid_amount' => $paidAmount,
            'description' => $response['transactionStatusDesc'] ?? null,
        ];
    }

    /**
     * GDC kadang membalas 500 Internal Server Error secara acak (bukan error di sisi kita).
     * Coba ulang dengan X-EXTERNAL-ID baru tiap percobaan — GDC menolak ID yang dipakai ulang.
     *
     * @return array{response:array,external_id:string}
     */
    protected function postWithRetry(string $path, array $body, int $attempts = 3): array
    {
        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $externalId = (string) Str::uuid();

            try {
                return [
                    'response' => $this->post($path, $body, now()->toIso8601String(), $externalId),
                    'external_id' => $externalId,
                ];
            } catch (\RuntimeException $e) {
                $isServerError = (bool) preg_match('/gagal \(5\d\d\)/', $e->getMessage());

                if (! $isServerError || $attempt === $attempts) {
                    throw $e;
                }

                usleep(400_000);
            }
        }
    }

    protected function post(string $path, array $body, string $timestamp, string $externalId): array
    {
        $accessToken = $this->getAccessToken();
        $signature = $this->signBody('POST', $path, $body, $accessToken, $timestamp);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'X-PARTNER-ID' => config('gdcpay.partner_id'),
            'X-EXTERNAL-ID' => $externalId,
            'CHANNEL-ID' => config('gdcpay.channel_id'),
            'Client-Key' => config('gdcpay.client_key'),
        ])->post($this->baseUrl.'/snap'.$path, $body);

        if ($response->failed()) {
            Log::warning('gdcpay.request_failed', [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Permintaan ke GDC Pay gagal ('.$response->status().') di '.$path.': '.Str::limit($response->body(), 500));
        }

        return $response->json() ?? [];
    }

    protected function signBody(string $method, string $path, array $body, string $accessToken, string $timestamp): string
    {
        $minifiedBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $bodyHash = hash('sha256', $minifiedBody);

        $stringToSign = sprintf('%s:%s:%s:%s:%s', $method, $path, $accessToken, $bodyHash, $timestamp);

        return base64_encode(hash_hmac('sha512', $stringToSign, config('gdcpay.client_secret'), true));
    }

    /**
     * Ambil Access Token B2B (SNAP), di-cache sesuai expiresIn dari GDC.
     */
    protected function getAccessToken(): string
    {
        return Cache::remember('gdcpay.access_token', 800, function () {
            $path = '/snap/v1.0/access-token/b2b';
            $timestamp = now()->toIso8601String();
            $signature = $this->signAsymmetric(trim((string) config('gdcpay.client_key')), $timestamp);

            $clientKey = trim((string) config('gdcpay.client_key'));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Timestamp' => $timestamp,
                'Client-Key' => $clientKey,
                // Nama header standar SNAP BI — dikirim juga untuk berjaga-jaga.
                'X-CLIENT-KEY' => $clientKey,
                'X-Signature' => $signature,
            ])->post($this->baseUrl.$path, ['grantType' => 'client_credentials']);

            if ($response->failed() || ! filled($response->json('accessToken'))) {
                Log::warning('gdcpay.access_token_failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \RuntimeException('Gagal mengambil Access Token GDC Pay ('.$response->status().'): '.Str::limit($response->body(), 500));
            }

            return $response->json('accessToken');
        });
    }

    /**
     * Signature asymmetric (RSA-SHA256) untuk Access Token B2B, pakai private key client.
     */
    protected function signAsymmetric(string $clientKey, string $timestamp): string
    {
        $privateKeyPath = config('gdcpay.private_key_path');

        if (! is_string($privateKeyPath) || ! is_file($privateKeyPath)) {
            throw new \RuntimeException('Private key GDC Pay tidak ditemukan di '.$privateKeyPath);
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        if ($privateKey === false) {
            throw new \RuntimeException('Private key GDC Pay tidak valid.');
        }

        $stringToSign = $clientKey.'|'.$timestamp;
        openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }
}
