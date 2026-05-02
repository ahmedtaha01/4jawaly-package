<?php

namespace AhmedTaha\FourjawalyPackage;

use AhmedTaha\FourjawalyPackage\DTO\FourJawalyDTO;
use AhmedTaha\FourjawalyPackage\Exceptions\FourJawalyException;
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
        $this->fourJawalyEndpoint = $this->getFourJawalyEndpoint();
    }

    public function send(array $phones, string $message): array
    {
        $fourJawalyDTO = new FourJawalyDTO($phones, $message);

        $appHash = $this->getAppHash();

        $messageTemplate = $this->buildMessageTemplate($fourJawalyDTO);

        try {

            $response = $this->sendSms($appHash, $messageTemplate);

            if ($response->failed()) {
                throw new FourJawalyException("Failed to send message: " . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new FourJawalyException("An error occurred: " . $e->getMessage());
        }
    }

    private function getFourJawalyEndpoint(): string
    {
        return 'https://api-sms.4jawaly.com/api/v1/account/area/sms/send';
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
        ])->post($this->fourJawalyEndpoint, $messageTemplate);
    }

    private function buildMessageTemplate(FourJawalyDTO $dto): array
    {
        return [
            "messages" => [
                [
                    "text" => $dto->getMessage(),
                    "numbers" => $dto->getPhones(),
                    "sender" => $this->sender,
                ],
            ],
        ];
    }

}
