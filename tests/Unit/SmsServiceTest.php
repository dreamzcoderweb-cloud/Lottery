<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SmsService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Config;

class SmsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure default configurations are set
        Config::set('sms.default', 'instantalerts');
        Config::set('sms.drivers.instantalerts', [
            'api_url' => 'https://instantalerts.in/api/smsapi',
            'api_key' => '5e08f2faa16717fc1aead8233b87f540',
            'route' => '2',
            'sender' => 'INSTNE',
            'template_id' => '1407168862906996721',
            'timeout' => 5,
        ]);
        Config::set('sms.otp', [
            'enabled' => true,
            'message_template' => 'Your OTP for {app_name} is {otp}. Please do not share this OTP.',
        ]);
        Config::set('sms.logging', [
            'enabled' => false,
            'channel' => 'stack',
        ]);
    }

    /**
     * Test successful OTP sending via InstantAlerts
     */
    public function testSendOtpSuccess(): void
    {
        // Mock success response from InstantAlerts
        $mock = new MockHandler([
            new Response(200, [], '10203057')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $smsService = new SmsService($client);
        $result = $smsService->sendOtp('9999999999', '123456');

        $this->assertTrue($result['success']);
        $this->assertEquals('OTP sent successfully.', $result['message']);
        $this->assertEquals('10203057', $result['reference']);
    }

    /**
     * Test successful OTP sending via InstantAlerts with spaced placeholders
     */
    public function testSendOtpWithSpacedPlaceholders(): void
    {
        Config::set('sms.otp.message_template', 'Your OTP for { app_name } is { otp }. Please do not share this OTP.');
        Config::set('app.name', 'MyTestApp');

        $container = [];
        $history = \GuzzleHttp\Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, [], '10203057')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client(['handler' => $handlerStack]);

        $smsService = new SmsService($client);
        $result = $smsService->sendOtp('9999999999', '123456');

        $this->assertTrue($result['success']);
        
        $this->assertCount(1, $container);
        $request = $container[0]['request'];
        $uri = $request->getUri();
        
        parse_str($uri->getQuery(), $queryParams);
        
        $this->assertEquals('Your OTP for MyTestApp is 123456. Please do not share this OTP.', $queryParams['sms'] ?? null);
    }

    /**
     * Test authentication error (code 101) handling
     */
    public function testSendOtpAuthError(): void
    {
        // Mock error response code 101
        $mock = new MockHandler([
            new Response(200, [], '101')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $smsService = new SmsService($client);
        $result = $smsService->sendOtp('9999999999', '123456');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid API key or authentication failed.', $result['message']);
        $this->assertNull($result['reference']);
    }

    /**
     * Test parameter validation error (code 102) handling
     */
    public function testSendOtpValidationError(): void
    {
        // Mock error response code 102
        $mock = new MockHandler([
            new Response(200, [], '102')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $smsService = new SmsService($client);
        $result = $smsService->sendOtp('9999999999', '123456');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid sender ID or validation error.', $result['message']);
        $this->assertNull($result['reference']);
    }

    /**
     * Test general Guzzle request exception handling
     */
    public function testSendOtpGuzzleFailure(): void
    {
        // Mock Guzzle request exception
        $mock = new MockHandler([
            new RequestException('Connection timeout', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $smsService = new SmsService($client);
        $result = $smsService->sendOtp('9999999999', '123456');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('InstantAlerts API request failed', $result['message']);
        $this->assertNull($result['reference']);
    }
}
