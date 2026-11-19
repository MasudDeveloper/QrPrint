<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BkashPgwService
{
    protected string $baseUrl;
    protected string $appKey;
    protected string $appSecret;
    protected string $username;
    protected string $password;
    protected bool $isSandbox;

    public function __construct()
    {
        $this->isSandbox = (bool) Setting::get('bkash_pgw_sandbox', true);
        $this->baseUrl = $this->isSandbox 
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta' 
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta';

        $this->appKey = Setting::get('bkash_pgw_app_key', env('BKASH_APP_KEY', ''));
        $this->appSecret = Setting::get('bkash_pgw_app_secret', env('BKASH_APP_SECRET', ''));
        $this->username = Setting::get('bkash_pgw_username', env('BKASH_USERNAME', ''));
        $this->password = Setting::get('bkash_pgw_password', env('BKASH_PASSWORD', ''));
    }

    public static function isEnabled(): bool
    {
        return (bool) Setting::get('bkash_pgw_enabled', false);
    }

    /**
     * Get or refresh bKash ID Token
     */
    public function grantToken(): ?string
    {
        if (empty($this->appKey) || empty($this->appSecret) || empty($this->username) || empty($this->password)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'username' => $this->username,
                'password' => $this->password,
            ])->post($this->baseUrl . '/tokenized/checkout/token/grant', [
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['id_token'] ?? null;
            }

            Log::warning('bKash Grant Token Failed: ' . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error('bKash Grant Token Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create bKash Payment URL
     */
    public function createPayment(float $amount, string $invoiceNumber, string $callbackUrl): array
    {
        $idToken = $this->grantToken();
        if (!$idToken) {
            return ['status' => 'error', 'message' => 'বিকাশ গেটওয়ে অথেনটিকেশন ব্যর্থ হয়েছে। ক্রেডেনশিয়াল চেক করুন।'];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $idToken,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/tokenized/checkout/create', [
                'mode' => '0011',
                'payerReference' => $invoiceNumber,
                'callbackURL' => $callbackUrl,
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $invoiceNumber,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['bkashURL'])) {
                    return [
                        'status' => 'success',
                        'paymentID' => $data['paymentID'],
                        'bkashURL' => $data['bkashURL'],
                    ];
                }
                return ['status' => 'error', 'message' => $data['statusMessage'] ?? 'পেমেন্ট তৈরি করতে ব্যর্থ'];
            }

            return ['status' => 'error', 'message' => 'বিকাশ সার্ভার রেসপন্স ত্রুটি: ' . $response->status()];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Execute bKash Payment after customer PIN entry
     */
    public function executePayment(string $paymentId): array
    {
        $idToken = $this->grantToken();
        if (!$idToken) {
            return ['status' => 'error', 'message' => 'টোকেন এক্সপায়ার হয়েছে'];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $idToken,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/tokenized/checkout/execute', [
                'paymentID' => $paymentId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['transactionStatus']) && $data['transactionStatus'] === 'Completed') {
                    return [
                        'status' => 'success',
                        'trxID' => $data['trxID'],
                        'amount' => (float) ($data['amount'] ?? 0),
                        'customerMsisdn' => $data['customerMsisdn'] ?? null,
                    ];
                }
                return ['status' => 'error', 'message' => $data['statusMessage'] ?? 'পেমেন্ট সফল হয়নি'];
            }

            return ['status' => 'error', 'message' => 'এক্সিকিউট রেসপন্স ত্রুটি'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

