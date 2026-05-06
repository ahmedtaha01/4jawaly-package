<?php

namespace AhmedTaha\FourjawalyPackage\Validation;

use AhmedTaha\FourjawalyPackage\Exceptions\FourJawalyException;


class FourJawalyValidation
{
    public static function validate(array $phones, string $message): void
    {
        self::validatePhones($phones);
        self::validateMessage($message);
    }

    protected static function validatePhones(array $phones): void
    {
        if (empty($phones)) {
            throw new FourJawalyException("Phones cannot be empty");
        }

        foreach ($phones as $phone) {
            if (!is_string($phone)) {
                throw new FourJawalyException("Each phone must be a string");
            }

            if (preg_match('/^\+/', $phone)) {
                throw new FourJawalyException("Phone number cannot start with +");
            }

            if (!preg_match('/^966/', $phone)) {
                throw new FourJawalyException("Phone number must start with 966");
            }

            if (!preg_match('/^966[0-9]{9}$/', $phone)) {
                throw new FourJawalyException("Phone number must be 9 digits after 966");
            }
        }
    }

    protected static function validateMessage(string $message): void
    {
        if (trim($message) === '') {
            throw new FourJawalyException("Message cannot be empty");
        }
    }
}