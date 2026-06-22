<?php

return [
    'default' => env('SMS_DRIVER', 'instantalerts'),

    'drivers' => [
        'instantalerts' => [
            'api_url' => env('INSTANTALERTS_API_URL', 'https://instantalerts.in/api/smsapi'),
            'api_key' => env('INSTANTALERTS_API_KEY'),
            'route' => env('INSTANTALERTS_ROUTE', '2'),
            'sender' => env('INSTANTALERTS_SENDER', 'INSTNE'),
            'template_id' => env('INSTANTALERTS_TEMPLATE_ID'),
            'timeout' => (int) env('INSTANTALERTS_TIMEOUT', 10),
        ],
    ],

    'otp' => [
        'enabled' => (bool) env('SMS_OTP_ENABLED', true),
        'message_template' => env('SMS_OTP_TEMPLATE', 'Your OTP for {app_name} is {otp}. Please do not share this OTP.'),
        'max_attempts' => (int) env('SMS_OTP_MAX_ATTEMPTS', 3),
        'attempt_reset_seconds' => (int) env('SMS_OTP_ATTEMPT_RESET', 3600),
    ],

    'logging' => [
        'enabled' => (bool) env('SMS_LOGGING_ENABLED', true),
        'channel' => env('SMS_LOG_CHANNEL', 'stack'),
    ],
];
