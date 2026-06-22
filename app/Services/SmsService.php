<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SmsService
{
    private Client $client;
    private array $config;

    public function __construct(?Client $client = null)
    {
        $this->config = Config::get('sms') ?? [];
        $this->client = $client ?? new Client();
    }

    /**
     * Send OTP to a customer's mobile number
     *
     * @param string $phoneNumber Customer's mobile number
     * @param string $otp The OTP code to send
     * @return array ['success' => bool, 'message' => string, 'reference' => ?string]
     */
    public function sendOtp(string $phoneNumber, string $otp): array
    {
        try {
            $this->validatePhoneNumber($phoneNumber);
            $this->validateOtp($otp);

            $driver = $this->config['default'];
            $driverConfig = $this->config['drivers'][$driver] ?? null;

            if (!$driverConfig) {
                throw new Exception("SMS driver '{$driver}' not configured.");
            }

            if ($driver === 'instantalerts') {
                return $this->sendViaInstantAlerts($phoneNumber, $otp, $driverConfig);
            }

            throw new Exception("Unknown SMS driver: {$driver}");

        } catch (Exception $e) {
            $this->logSmsError($phoneNumber, $otp, $e);
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'reference' => null,
            ];
        }
    }

    /**
     * Send OTP via InstantAlerts API
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param array $config
     * @return array
     */
    private function sendViaInstantAlerts(string $phoneNumber, string $otp, array $config): array
    {
        $apiKey = $config['api_key'] ?? null;
        $templateId = $config['template_id'] ?? null;

        if (!$apiKey || !$templateId) {
            throw new Exception('InstantAlerts API Key or Template ID not configured.');
        }

        $message = $this->buildOtpMessage($otp);

        $params = [
            'key' => $apiKey,
            'route' => $config['route'] ?? '2',
            'sender' => $config['sender'] ?? 'INSTNE',
            'number' => $phoneNumber,
            'templateid' => $templateId,
            'sms' => $message,
        ];

        try {
            $response = $this->client->get($config['api_url'], [
                'query' => $params,
                'timeout' => $config['timeout'] ?? 10,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($statusCode === 200) {
                $trimmedBody = trim($body);

                // Handle 3-digit error codes returned by InstantAlerts even on HTTP 200
                if (is_numeric($trimmedBody) && strlen($trimmedBody) === 3) {
                    $errorMap = [
                        '101' => 'Invalid API key or authentication failed.',
                        '102' => 'Invalid sender ID or validation error.',
                        '103' => 'Invalid contact(s)',
                        '104' => 'Invalid route',
                        '105' => 'Invalid message',
                        '106' => 'Spam blocked',
                        '107' => 'Promotional block',
                        '108' => 'Low credits in the specified route',
                        '109' => 'Promotional route will be working from 10am to 09pm only',
                        '110' => 'Invalid DLT Template ID',
                        '111' => 'Invalid Schedule Time'
                    ];
                    $errorMessage = $errorMap[$trimmedBody] ?? "InstantAlerts API error code: {$trimmedBody}";
                    throw new Exception($errorMessage);
                }

                $this->logSmsSuccess($phoneNumber, $otp, $body);

                return [
                    'success' => true,
                    'message' => 'OTP sent successfully.',
                    'reference' => $this->extractReferenceId($body),
                ];
            }

            throw new Exception("InstantAlerts API returned status code {$statusCode}");

        } catch (GuzzleException $e) {
            throw new Exception("InstantAlerts API request failed: " . $e->getMessage());
        }
    }

    /**
     * Build the OTP message from configuration template
     *
     * @param string $otp
     * @return string
     */
    private function buildOtpMessage(string $otp): string
    {
        $template = $this->config['otp']['message_template'] ?? 'Your OTP for {app_name} is {otp}. Please do not share this OTP.';
        
        $replacements = [
            '{otp}'          => $otp,
            '{ otp }'        => $otp,
            '{otp }'         => $otp,
            '{ otp}'         => $otp,
            '{app_name}'     => config('app.name'),
            '{ app_name }'   => config('app.name'),
            '{app_name }'    => config('app.name'),
            '{ app_name}'    => config('app.name'),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );
    }

    /**
     * Extract reference ID from InstantAlerts response
     *
     * @param string $response
     * @return string|null
     */
    private function extractReferenceId(string $response): ?string
    {
        // InstantAlerts typically returns a message ID of length 5 to 30 digits/characters
        $response = trim($response);
        if (preg_match('/^[a-zA-Z0-9]{5,30}$/', $response)) {
            return $response;
        }
        return null;
    }

    /**
     * Validate phone number format
     *
     * @param string $phoneNumber
     * @return void
     * @throws Exception
     */
    private function validatePhoneNumber(string $phoneNumber): void
    {
        // Remove spaces, dashes, and plus signs
        $cleaned = preg_replace('/[\s\-\+]/', '', $phoneNumber);

        // Check if it's a valid phone number (10-15 digits)
        if (!preg_match('/^\d{10,15}$/', $cleaned)) {
            throw new Exception('Invalid phone number format.');
        }
    }

    /**
     * Validate OTP format
     *
     * @param string $otp
     * @return void
     * @throws Exception
     */
    private function validateOtp(string $otp): void
    {
        if (!preg_match('/^\d{4,10}$/', $otp)) {
            throw new Exception('Invalid OTP format.');
        }
    }

    /**
     * Log successful SMS send
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param string $response
     * @return void
     */
    private function logSmsSuccess(string $phoneNumber, string $otp, string $response): void
    {
        $logging = $this->config['logging'] ?? [];
        if (($logging['enabled'] ?? true) !== true) {
            return;
        }

        $channel = $logging['channel'] ?? 'stack';
        Log::channel($channel)->info('SMS OTP sent successfully', [
            'phone_number' => $this->maskPhoneNumber($phoneNumber),
            'otp_length' => strlen($otp),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log SMS send failure
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param Exception $exception
     * @return void
     */
    private function logSmsError(string $phoneNumber, string $otp, Exception $exception): void
    {
        $logging = $this->config['logging'] ?? [];
        if (($logging['enabled'] ?? true) !== true) {
            return;
        }

        $channel = $logging['channel'] ?? 'stack';
        Log::channel($channel)->error('SMS OTP send failed', [
            'phone_number' => $this->maskPhoneNumber($phoneNumber),
            'otp_length' => strlen($otp),
            'error' => $exception->getMessage(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Mask phone number for logging purposes
     *
     * @param string $phoneNumber
     * @return string
     */
    private function maskPhoneNumber(string $phoneNumber): string
    {
        $cleaned = preg_replace('/[\s\-\+]/', '', $phoneNumber);
        $length = strlen($cleaned);

        if ($length < 4) {
            return str_repeat('*', $length);
        }

        $visible = substr($cleaned, -4);
        return str_repeat('*', $length - 4) . $visible;
    }
}
