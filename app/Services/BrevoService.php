<?php

namespace App\Services;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoService
{
    protected $apiInstance;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.api_key'));
        $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);
    }

    public function sendEmail($toEmail, $toName, $subject, $htmlContent)
    {
        $sendSmtpEmail = new SendSmtpEmail([
            'to' => [[ 'email' => $toEmail, 'name' => $toName ]],
            'sender' => [ 'email' => 'nganhkhoa.becloud@gmail.com', 'name' => 'Repairs Searching' ],
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ]);

        return $this->apiInstance->sendTransacEmail($sendSmtpEmail);
    }
}
