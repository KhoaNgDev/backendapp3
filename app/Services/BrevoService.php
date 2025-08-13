<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key');
    }

    public function sendEmail(string $to, string $subject, string $htmlContent): void
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'api-key' => $this->apiKey,
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [
                ['email' => $to],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        if ($response->failed()) {
            throw new \Exception('Gửi email thất bại: ' . $response->body());
        }
    }
}
