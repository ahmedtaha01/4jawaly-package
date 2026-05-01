<?php

namespace AhmedTaha\FourjawalyPackage;

use AhmedTaha\FourjawalyPackage\Exceptions\FourJawalyException;
use Illuminate\Support\Facades\Http;

class FourJawalyService
{

    private string $apiKey;
    private string $apiSecret;
    private string $sender;

    public function __construct()
    {
        $this->apiKey = (string) config('fourjawaly.api_key');
        $this->apiSecret = (string) config('fourjawaly.api_secret');
        $this->sender = (string) config('fourjawaly.sender_name');
    }

    public function sendMessage(array $phones, string $message): array
    {
        $app_hash = $this->getAppHash();

        $fourJawalyEndpoint = $this->getFourJawalyEndpoint();

        $messageTemplate = $this->buildMessageTemplate($phones,$message);

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $app_hash,
            ])->post($fourJawalyEndpoint, $messageTemplate);

            if ($response->failed()) {
                throw new FourJawalyException("Failed to send message: " . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new FourJawalyException("An error occurred: " . $e->getMessage());
        }
    }

    private function getAppHash()
    {
        return base64_encode("{$this->apiKey}:{$this->apiSecret}");
    }

    private function getFourJawalyEndpoint()
    {
        return 'https://api-sms.4jawaly.com/api/v1/account/area/sms/send';
    }

    private function buildMessageTemplate($phones, $message)
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
