<?php

return [
    'withdrawal' => [
        // When to debit wallet balance: 'request' or 'approval'
        'deduct_on' => env('WALLET_WITHDRAW_DEDUCT_ON', 'approval'),

        'min_amount' => (float) env('WALLET_WITHDRAW_MIN', 100),
        'max_amount' => (float) env('WALLET_WITHDRAW_MAX', 50000),

        // OTP required before creating withdrawal request
        'otp' => [
            'enabled' => (bool) env('WALLET_WITHDRAW_OTP_ENABLED', true),
            'ttl_seconds' => (int) env('WALLET_WITHDRAW_OTP_TTL', 300),
            'send_via_sms' => (bool) env('WALLET_WITHDRAW_OTP_SEND_SMS', true),
        ],
    ],
];
