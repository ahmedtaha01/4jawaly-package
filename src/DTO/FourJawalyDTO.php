<?php

namespace AhmedTaha\FourjawalyPackage\DTO;

class FourJawalyDTO
{
    public string $message;
    public array $phones;

    public function __construct(array $phones, string $message)
    {
        $this->phones = $this->validatePhones($phones);
        $this->message = $this->validateMessage($message);
    }

    protected function validatePhones(array $phones): array
    {
        if (empty($phones)) {
            throw new \InvalidArgumentException("Phones cannot be empty");
        }

        foreach ($phones as $phone) {
            if (!is_string($phone)) {
                throw new \InvalidArgumentException("Each phone must be a string");
            }

            if (preg_match('/^\+/', $phone)) {
                throw new \InvalidArgumentException("Phone number cannot start with +");
            }

            if (!preg_match('/^966/', $phone)) {
                throw new \InvalidArgumentException("Phone number must start with 966");
            }

            if (!preg_match('/^966[0-9]{9}$/', $phone)) {
                throw new \InvalidArgumentException("Phone number must be 9 digits after 966");
            }
        }

        return $phones;
    }

    protected function validateMessage(string $message): string
    {
        if (trim($message) === '') {
            throw new \InvalidArgumentException("Message cannot be empty");
        }

        return $message;
    }

    public function getPhones(): array
    {
        return $this->phones;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
