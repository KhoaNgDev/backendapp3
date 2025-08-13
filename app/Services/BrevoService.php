<?php

namespace App\Services;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use Illuminate\Support\Facades\Http;

class BrevoService
{
    protected $api;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
        $this->api = new TransactionalEmailsApi(null, $config);
    }

    public function sendEmail($to, $subject, $htmlContent)
    {
        $sendSmtpEmail = new SendSmtpEmail([
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

        return $this->api->sendTransacEmail($sendSmtpEmail);
    }
}
