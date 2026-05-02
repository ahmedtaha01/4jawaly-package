<?php

namespace AhmedTaha\FourjawalyPackage\Tests\Unit;

use AhmedTaha\FourjawalyPackage\DTO\FourJawalyDTO;
use AhmedTaha\FourjawalyPackage\Tests\TestCase;
use InvalidArgumentException;

class DTOTest extends TestCase
{
    public function test_it_accepts_valid_data()
    {
        $dto = new FourJawalyDTO(
            ['966501234567'],
            'hello'
        );

        $this->assertIsArray($dto->phones);
        $this->assertEquals('hello', $dto->message);
    }

    public function test_it_fails_with_empty_phones()
    {
        $this->expectException(InvalidArgumentException::class);

        new FourJawalyDTO([], 'hello');
    }

    public function test_it_fails_with_empty_message()
    {
        $this->expectException(InvalidArgumentException::class);

        new FourJawalyDTO(['966501234567'], '');
    }

    public function test_it_fails_when_phone_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);

        new FourJawalyDTO(['96687452156656369'], 'hello');
    }
}