<?php

namespace AhmedTaha\FourjawalyPackage\Tests\Feature;

use AhmedTaha\FourjawalyPackage\FourJawalyService;
use AhmedTaha\FourjawalyPackage\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SendSmsTest extends TestCase
{
    public function test_it_sends_sms_successfully()
    {
        // Arrange: fake HTTP response
        Http::fake([
            '*' => Http::response([
                'status' => 'success',
                'message' => 'sent'
            ], 200)
        ]);

        // Act: create service + DTO
        $service = new FourJawalyService();

        $response = $service->send(['966501234567'], 'hello world');
        // Assert
        $this->assertIsArray($response);
        $this->assertEquals('success', $response['status']);
    }
}