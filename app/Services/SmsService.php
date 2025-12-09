<?php

namespace App\Services;

use App\Models\SmsProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send($to, $message, $extra = [])
    {
        $provider = SmsProvider::where('active', SmsProvider::ACTIVE)
            ->orderBy('id', 'desc')
            ->first();
        if (!$provider) {
            Log::error('No active SMS provider found.');
            return false;
        }
        $params = self::replacePlaceholders($provider->params ?? [], $to, $message, $extra);
        $headers = self::replacePlaceholders($provider->headers ?? [], $to, $message, $extra);
        try {
            $response = match ($provider->method) {
                'POST' => Http::withHeaders($headers)->asForm()->post($provider->base_url, $params),
                'GET' => Http::withHeaders($headers)->get($provider->base_url, $params),
            };
            Log::info('SMS sent', [
                'to' => $to,
                'message' => $message,
                'response' => $response->body()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS sending failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    private static function replacePlaceholdersText($message, $data = [])
    {
        $replace = [
            '<invester_name>' => $data['investor_name'] ?? '',
            '<investor_amount>' => $data['investor_amount'] ?? '',
            '<customer_name>' => $data['customer_name'] ?? '',
            '<installment_amount>' => $data['installment_amount'] ?? '',
            '<month_name>' => $data['month_name'] ?? '',
            '<due_date>' => $data['due_date'] ?? '',
            '<shop_name>' => $data['shop_name'] ?? '',
        ];

        return str_replace(array_keys($replace), array_values($replace), $message);
    }
}
