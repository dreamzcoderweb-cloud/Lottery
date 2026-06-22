# OTP SMS Integration Documentation

## Overview

This document provides comprehensive instructions for configuring and using the OTP (One-Time Password) SMS functionality integrated with the InstantAlerts SMS service.

## Table of Contents

1. [Requirements](#requirements)
2. [Environment Configuration](#environment-configuration)
3. [API Credentials](#api-credentials)
4. [Configuration Files](#configuration-files)
5. [Architecture](#architecture)
6. [Usage](#usage)
7. [Error Handling](#error-handling)
8. [Testing](#testing)
9. [Troubleshooting](#troubleshooting)
10. [Security Considerations](#security-considerations)

---

## Requirements

- Laravel 11.x
- PHP 8.1+
- Guzzle HTTP Client (included with Laravel)
- Active InstantAlerts account with SMS API access
- Valid API Key and Template ID from InstantAlerts

---

## Environment Configuration

### 1. Copy Environment File

If you haven't already, copy the `.env.example` to `.env`:

```bash
cp .env.example .env
```

### 2. Add SMS Credentials to `.env`

Add the following variables to your `.env` file:

```env
# SMS Configuration
SMS_DRIVER=instantalerts
SMS_OTP_ENABLED=true
SMS_OTP_TEMPLATE=Your OTP for {app_name} is {otp}. Please do not share this OTP.
SMS_OTP_MAX_ATTEMPTS=3
SMS_OTP_ATTEMPT_RESET=3600
SMS_LOGGING_ENABLED=true
SMS_LOG_CHANNEL=stack

# InstantAlerts API Configuration
INSTANTALERTS_API_URL=https://instantalerts.in/api/smsapi
INSTANTALERTS_API_KEY=5e08f2faa16717fc1aead8233b87f540
INSTANTALERTS_ROUTE=2
INSTANTALERTS_SENDER=INSTNE
INSTANTALERTS_TEMPLATE_ID=1407168862906996721
INSTANTALERTS_TIMEOUT=10
```

### 3. Wallet OTP Configuration

Configure wallet withdrawal OTP settings:

```env
# Wallet Withdrawal OTP
WALLET_WITHDRAW_OTP_ENABLED=true
WALLET_WITHDRAW_OTP_TTL=300  # OTP expires after 5 minutes
WALLET_WITHDRAW_DEDUCT_ON=approval  # or 'request'
WALLET_WITHDRAW_MIN=100
WALLET_WITHDRAW_MAX=50000
```

---

## API Credentials

### InstantAlerts Configuration

| Variable | Value | Description |
|----------|-------|-------------|
| `INSTANTALERTS_API_KEY` | `5e08f2faa16717fc1aead8233b87f540` | API authentication key |
| `INSTANTALERTS_TEMPLATE_ID` | `1407168862906996721` | SMS template ID for OTP messages |
| `INSTANTALERTS_ROUTE` | `2` | SMS routing option |
| `INSTANTALERTS_SENDER` | `INSTNE` | Sender ID/Name |
| `INSTANTALERTS_API_URL` | `https://instantalerts.in/api/smsapi` | API endpoint URL |
| `INSTANTALERTS_TIMEOUT` | `10` | Request timeout in seconds |

### Obtaining Credentials

1. Log in to your InstantAlerts account at [https://instantalerts.in](https://instantalerts.in)
2. Navigate to API settings or Integration
3. Copy your API Key
4. Create or use an existing SMS template (ID: `1407168862906996721`)
5. Verify your sender ID is configured as `INSTNE`

---

## Configuration Files

### 1. `config/sms.php`

This file contains all SMS-related configuration:

```php
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
```

### 2. `config/wallet.php`

OTP settings for wallet withdrawals:

```php
'otp' => [
    'enabled' => (bool) env('WALLET_WITHDRAW_OTP_ENABLED', true),
    'ttl_seconds' => (int) env('WALLET_WITHDRAW_OTP_TTL', 300),  // 5 minutes
]
```

---

## Architecture

### Components

#### 1. **SmsService** (`app/Services/SmsService.php`)
- Handles SMS sending to third-party providers
- Integrates with InstantAlerts API
- Validates phone numbers and OTP format
- Implements error handling and logging
- Masks sensitive data in logs

Key methods:
- `sendOtp(phoneNumber, otp)` - Send OTP to a phone number
- `sendViaInstantAlerts()` - InstantAlerts API integration
- `buildOtpMessage()` - Generate SMS message from template
- `validatePhoneNumber()` - Validate phone number format
- `validateOtp()` - Validate OTP format

#### 2. **WalletWithdrawalService** (`app/Services/WalletWithdrawalService.php`)
- Manages wallet withdrawal operations
- Integrates OTP verification
- Calls `SmsService` to send OTP
- Implements rate limiting for OTP requests (30-second cooldown)
- Manages OTP cache and expiration

Key methods:
- `sendOtp(customerId)` - Generate and send OTP via SMS
- `createWithdrawal(customerId, data)` - Create withdrawal with OTP verification
- `verifyOtpForCustomer()` - Verify provided OTP

#### 3. **WalletWithdrawalController** (`app/Http/Controllers/Api/WalletWithdrawalController.php`)
- API endpoints for wallet withdrawals
- Endpoint: `POST /api/wallet-withdrawals/otp/send` - Request OTP

---

## Usage

### 1. Sending OTP

**Endpoint:** `POST /api/wallet-withdrawals/otp/send`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Response (Success):**
```json
{
  "status": true,
  "message": "OTP sent.",
  "data": {
    "sent": true,
    "otp_code": null,  // Only shown in debug mode
    "message": "OTP sent to registered mobile number.",
    "expires_in": 300
  }
}
```

**Response (Error - Mobile not configured):**
```json
{
  "status": false,
  "message": "Mobile number not found for this customer."
}
```

### 2. Creating Withdrawal with OTP

**Endpoint:** `POST /api/wallet-withdrawals`

**Request Body:**
```json
{
  "amount": 5000,
  "otp_code": "123456",
  "remarks": "Monthly withdrawal"
}
```

**Response (Success):**
```json
{
  "status": true,
  "message": "Withdrawal request created.",
  "withdrawal": {
    "wallet_withdrawal_id": 1,
    "customer_id": 1,
    "amount": 5000,
    "status": "pending",
    "created_at": "2026-06-19T10:00:00Z"
  }
}
```

---

## Error Handling

### Common Error Messages

| Error | Cause | Solution |
|-------|-------|----------|
| "OTP is not enabled." | OTP feature is disabled | Enable OTP in `.env` |
| "Mobile number not found for this customer." | Customer has no phone | Update customer profile with mobile |
| "Please wait before requesting a new OTP." | Requested too quickly | Wait 30 seconds before retry |
| "OTP expired." | OTP TTL exceeded | Request new OTP |
| "Invalid OTP." | Wrong OTP provided | Verify OTP and try again |
| "Failed to send OTP. Please try again." | SMS API failure | Check API credentials and logs |

### Logging

All SMS operations are logged to `storage/logs/`:

**Success Log:**
```
[2026-06-19 10:00:00] stack.INFO: SMS OTP sent successfully {"phone_number":"****1234","otp_length":6,"timestamp":"2026-06-19T10:00:00Z"}
```

**Error Log:**
```
[2026-06-19 10:05:00] stack.ERROR: SMS OTP send failed {"phone_number":"****1234","otp_length":6,"error":"InstantAlerts API returned status code 401","timestamp":"2026-06-19T10:05:00Z"}
```

---

## Testing

### Development Mode

In development (when `APP_DEBUG=true`), the API response includes the generated OTP code:

```json
{
  "status": true,
  "data": {
    "sent": true,
    "otp_code": "123456",  // Visible in debug mode
    "expires_in": 300
  }
}
```

### Production Mode

In production (when `APP_DEBUG=false`), the OTP code is NOT returned:

```json
{
  "status": true,
  "data": {
    "sent": true,
    "otp_code": null,  // Null in production
    "expires_in": 300
  }
}
```

### Testing with cURL

```bash
# Request OTP
curl -X POST http://localhost/api/wallet-withdrawals/otp/send \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"

# Create withdrawal with OTP
curl -X POST http://localhost/api/wallet-withdrawals \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 5000,
    "otp_code": "123456"
  }'
```

---

## Troubleshooting

### Issue: "Failed to send OTP. Please try again."

**Causes:**
1. Invalid API Key
2. Template ID not configured
3. Network connectivity issues
4. InstantAlerts API is down

**Solution:**
1. Verify `INSTANTALERTS_API_KEY` and `INSTANTALERTS_TEMPLATE_ID` in `.env`
2. Check API endpoint URL: `https://instantalerts.in/api/smsapi`
3. Verify phone number format (should be 10-15 digits)
4. Check Laravel logs: `tail -f storage/logs/laravel.log`

### Issue: "Invalid phone number format."

**Causes:**
1. Phone number is too short/long
2. Contains invalid characters

**Solution:**
1. Ensure phone number is 10-15 digits
2. Remove +, spaces, or dashes from phone number
3. Update customer phone number if needed

### Issue: OTP Not Received

**Causes:**
1. Phone number is incorrect
2. InstantAlerts sender is blacklisted
3. Customer's carrier is blocking messages
4. API rate limiting

**Solution:**
1. Verify customer's phone number
2. Check InstantAlerts account for sender verification
3. Wait 30 seconds before requesting new OTP
4. Check InstantAlerts dashboard for delivery status

### Issue: OTP Expired Before Use

**Cause:** User took longer than TTL to enter OTP

**Solution:**
1. Increase `WALLET_WITHDRAW_OTP_TTL` in `.env` (default: 300 seconds)
2. Request a new OTP and use it within TTL

---

## Security Considerations

### 1. **Credentials Management**
- Store all sensitive credentials in `.env`
- Never commit `.env` to version control
- Use strong, unique API keys
- Rotate API keys periodically

### 2. **Phone Number Privacy**
- Phone numbers are masked in logs (****1234)
- Sensitive data is never logged in full

### 3. **OTP Security**
- OTP is hashed before caching (bcrypt)
- OTP expires after TTL (default 5 minutes)
- Rate limiting prevents spam (30-second cooldown)
- OTP is cleared on successful verification

### 4. **API Communication**
- API calls use HTTPS
- Timeout set to prevent hanging requests
- Error messages don't expose sensitive details

### 5. **Database Security**
- Customer mobile numbers should be encrypted at rest
- Consider using Laravel's encryption for sensitive fields

### 6. **Production Deployment**
- Set `APP_DEBUG=false` to hide OTP codes
- Configure strong database passwords
- Use environment-specific credentials
- Enable HTTPS/SSL on production
- Monitor failed OTP attempts
- Set up SMS rate limiting per customer

---

## Configuration Examples

### Development Environment (.env)

```env
APP_DEBUG=true
APP_ENV=local

SMS_DRIVER=instantalerts
SMS_OTP_ENABLED=true
SMS_LOGGING_ENABLED=true

INSTANTALERTS_API_KEY=5e08f2faa16717fc1aead8233b87f540
INSTANTALERTS_TEMPLATE_ID=1407168862906996721
INSTANTALERTS_ROUTE=2
INSTANTALERTS_SENDER=INSTNE

WALLET_WITHDRAW_OTP_ENABLED=true
WALLET_WITHDRAW_OTP_TTL=300
```

### Production Environment (.env)

```env
APP_DEBUG=false
APP_ENV=production

SMS_DRIVER=instantalerts
SMS_OTP_ENABLED=true
SMS_LOGGING_ENABLED=true

INSTANTALERTS_API_KEY=your_production_api_key_here
INSTANTALERTS_TEMPLATE_ID=your_production_template_id_here
INSTANTALERTS_ROUTE=2
INSTANTALERTS_SENDER=YourSender

WALLET_WITHDRAW_OTP_ENABLED=true
WALLET_WITHDRAW_OTP_TTL=300
SMS_LOG_CHANNEL=daily  # Use daily log rotation in production
```

---

## Future Enhancements

1. **Multiple SMS Providers** - Add support for other SMS APIs
2. **Email Fallback** - Send OTP via email if SMS fails
3. **OTP History** - Track sent OTPs for audit purposes
4. **Webhook Support** - Handle delivery receipts from InstantAlerts
5. **Customizable Templates** - Per-user SMS templates
6. **Analytics** - Track OTP delivery rates and success metrics

---

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Review `.env` configuration
3. Contact InstantAlerts support: [https://instantalerts.in/support](https://instantalerts.in/support)
4. Check application documentation

---

**Last Updated:** 2026-06-19
**Version:** 1.0.0
