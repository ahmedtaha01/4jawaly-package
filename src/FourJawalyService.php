<?php

namespace AhmedTaha\FourjawalyPackage;

use AhmedTaha\FourjawalyPackage\Exceptions\FourJawalyException;
use AhmedTaha\FourjawalyPackage\Validation\FourJawalyValidation;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class FourJawalyService
{

    private string $apiKey;
    private string $apiSecret;
    private string $sender;
    private string $fourJawalyEndpoint;

    public function __construct()
    {
        $this->apiKey = (string) config('fourjawaly.api_key');
        $this->apiSecret = (string) config('fourjawaly.api_secret');
        $this->sender = (string) config('fourjawaly.sender_name');
        $this->fourJawalyEndpoint = config('fourjawaly.four_jawaly_base_url');
    }

    public function send(array $phones, string $message): array
    {
        FourJawalyValidation::validate($phones, $message);

        $appHash = $this->getAppHash();

        $messageTemplate = $this->buildMessageTemplate($phones, $message);

        $response = $this->sendSms($appHash, $messageTemplate);

        if ($response->failed()) {
            throw new FourJawalyException("Failed to send message: " . $response->body());
        }

        return $response->json();
    }

    private function getAppHash()
    {
        return base64_encode("{$this->apiKey}:{$this->apiSecret}");
    }

    private function sendSms(string $appHash, array $messageTemplate): Response
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $appHash,
        ])->post($this->fourJawalyEndpoint.'/account/area/sms/send', $messageTemplate);
    }

    private function buildMessageTemplate(array $phones, string $message): array
    {
        return [
            "messages" => [
                [
                    "text" => $message,
                    "numbers" => $phones,
                    "sender" => $this->sender,
                ],
            ],
        ];
    }

}
