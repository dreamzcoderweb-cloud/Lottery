# OTP API Endpoints Documentation

## Overview

This document provides detailed API endpoint documentation for the OTP (One-Time Password) functionality.

## Endpoints

### 1. Send OTP

Send an OTP to the customer's registered mobile number.

**Endpoint:** `POST /api/wallet-withdrawals/otp/send`

**Authentication:** Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{}
```

**Success Response (200):**
```json
{
  "status": true,
  "message": "OTP sent.",
  "data": {
    "sent": true,
    "otp_code": "123456",  // Only in development mode (APP_DEBUG=true)
    "message": "OTP sent to registered mobile number.",
    "expires_in": 300
  }
}
```

**Error Response (422):**
```json
{
  "status": false,
  "message": "Mobile number not found for this customer."
}
```

**Possible Error Messages:**

| Message | HTTP Status | Cause | Solution |
|---------|------------|-------|----------|
| "OTP is not enabled." | 422 | OTP feature disabled | Enable `WALLET_WITHDRAW_OTP_ENABLED` |
| "Mobile number not found for this customer." | 422 | No phone on account | Update customer profile |
| "Please wait before requesting a new OTP." | 422 | Rate limited (30 sec) | Wait 30 seconds |
| "Failed to send OTP. Please try again." | 422 | SMS API failed | Check credentials & logs |
| "Unauthenticated." | 401 | No valid token | Provide valid Bearer token |

---

### 2. Create Wallet Withdrawal

Create a withdrawal request with OTP verification.

**Endpoint:** `POST /api/wallet-withdrawals`

**Authentication:** Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "amount": 5000,
  "otp_code": "123456",
  "remarks": "Monthly salary withdrawal"
}
```

**Field Specifications:**

| Field | Type | Required | Rules | Example |
|-------|------|----------|-------|---------|
| `amount` | float | Yes | numeric, min:0.01 | 5000 |
| `otp_code` | string | Yes* | string, min:4, max:10 | "123456" |
| `remarks` | string | No | string, max:2000 | "Monthly withdrawal" |

*Required if `WALLET_WITHDRAW_OTP_ENABLED=true`

**Success Response (201):**
```json
{
  "status": true,
  "message": "Withdrawal request created.",
  "withdrawal": {
    "wallet_withdrawal_id": 1,
    "customer_id": 5,
    "amount": 5000,
    "status": "pending",
    "remarks": "Monthly salary withdrawal",
    "created_at": "2026-06-19T10:30:00Z",
    "updated_at": "2026-06-19T10:30:00Z"
  }
}
```

**Error Response (422):**
```json
{
  "status": false,
  "message": "Invalid OTP."
}
```

**Possible Error Messages:**

| Message | Cause | Solution |
|---------|-------|----------|
| "Insufficient wallet balance." | Not enough balance | Add funds to wallet |
| "Invalid OTP." | Wrong OTP code | Request new OTP and try again |
| "OTP expired." | TTL exceeded | Request new OTP |
| "OTP code is required." | OTP not sent | Send OTP first |
| "Minimum withdrawal amount is 100." | Amount below limit | Increase amount |
| "Maximum withdrawal amount is 50000." | Amount above limit | Decrease amount |
| "Bank account or UPI details not found." | No bank info | Add bank/UPI details |

---

### 3. Get Withdrawal Balance and Limits

Get current wallet balance and withdrawal limits.

**Endpoint:** `GET /api/wallet-withdrawals/validate-balance`

**Authentication:** Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
Accept: application/json
```

**Success Response (200):**
```json
{
  "status": true,
  "wallet_balance": 50000,
  "limits": {
    "min": 100,
    "max": 50000,
    "deduct_on": "approval"
  },
  "otp_enabled": true
}
```

**Field Descriptions:**

| Field | Type | Description |
|-------|------|-------------|
| `wallet_balance` | float | Current available balance |
| `limits.min` | float | Minimum withdrawal amount |
| `limits.max` | float | Maximum withdrawal amount |
| `limits.deduct_on` | string | When balance is deducted: "request" or "approval" |
| `otp_enabled` | boolean | Whether OTP is required |

---

### 4. Get Withdrawal History

Retrieve customer's withdrawal request history.

**Endpoint:** `GET /api/wallet-withdrawals`

**Authentication:** Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
Accept: application/json
```

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number for pagination |

**Success Response (200):**
```json
{
  "status": true,
  "message": "Withdrawal history.",
  "data": {
    "data": [
      {
        "wallet_withdrawal_id": 2,
        "customer_id": 5,
        "amount": 3000,
        "status": "approved",
        "remarks": "Previous withdrawal",
        "created_at": "2026-06-18T15:00:00Z",
        "updated_at": "2026-06-18T16:00:00Z"
      },
      {
        "wallet_withdrawal_id": 1,
        "customer_id": 5,
        "amount": 5000,
        "status": "pending",
        "remarks": "Monthly withdrawal",
        "created_at": "2026-06-19T10:30:00Z",
        "updated_at": "2026-06-19T10:30:00Z"
      }
    ],
    "links": {
      "first": "http://localhost/api/wallet-withdrawals?page=1",
      "last": "http://localhost/api/wallet-withdrawals?page=1",
      "prev": null,
      "next": null
    },
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": 1,
      "path": "http://localhost/api/wallet-withdrawals",
      "per_page": 20,
      "to": 2,
      "total": 2
    }
  }
}
```

---

### 5. Get Withdrawal Details

Get details of a specific withdrawal request.

**Endpoint:** `GET /api/wallet-withdrawals/{id}`

**Authentication:** Required (Bearer Token)

**Path Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | Withdrawal request ID |

**Success Response (200):**
```json
{
  "status": true,
  "message": "Withdrawal details.",
  "withdrawal": {
    "wallet_withdrawal_id": 1,
    "customer_id": 5,
    "amount": 5000,
    "status": "pending",
    "remarks": "Monthly withdrawal",
    "created_at": "2026-06-19T10:30:00Z",
    "updated_at": "2026-06-19T10:30:00Z"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Not Found"
}
```

---

## Workflow Example

### Complete OTP Verification Flow

```bash
# Step 1: Check balance and OTP requirement
curl -X GET http://localhost/api/wallet-withdrawals/validate-balance \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"

Response:
{
  "status": true,
  "wallet_balance": 50000,
  "limits": {"min": 100, "max": 50000},
  "otp_enabled": true
}

# Step 2: Request OTP
curl -X POST http://localhost/api/wallet-withdrawals/otp/send \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"

Response:
{
  "status": true,
  "message": "OTP sent.",
  "data": {
    "sent": true,
    "otp_code": "123456",  // Dev mode only
    "expires_in": 300
  }
}

# Step 3: Customer receives SMS and enters OTP
# (In development, OTP shown in response above)

# Step 4: Submit withdrawal with OTP
curl -X POST http://localhost/api/wallet-withdrawals \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 5000,
    "otp_code": "123456",
    "remarks": "Monthly withdrawal"
  }'

Response:
{
  "status": true,
  "message": "Withdrawal request created.",
  "withdrawal": {
    "wallet_withdrawal_id": 1,
    "customer_id": 5,
    "amount": 5000,
    "status": "pending"
  }
}
```

---

## Status Codes

| Code | Meaning | Common Cause |
|------|---------|--------------|
| 200 | Success | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid request format |
| 401 | Unauthorized | Missing/invalid token |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Server Error | Internal server error |

---

## Rate Limiting

- **OTP Requests:** Max 1 request per 30 seconds per customer
- **API Calls:** Standard Laravel rate limiting applies
- **SMS API:** Based on InstantAlerts plan

---

## Authentication

All endpoints require Bearer token authentication:

```
Authorization: Bearer {access_token}
```

Obtain token via login endpoint (varies by application).

---

## Response Format

All responses follow a standard format:

**Success:**
```json
{
  "status": true,
  "message": "Success message",
  "data": {}
}
```

**Error:**
```json
{
  "status": false,
  "message": "Error message"
}
```

---

## Testing Examples

### Using Postman

1. **Import Collection**
   - Copy the endpoints above into Postman
   - Set variable `{{base_url}}` to `http://localhost`
   - Set variable `{{token}}` to your Bearer token

2. **Test OTP Sending**
   ```
   POST {{base_url}}/api/wallet-withdrawals/otp/send
   Headers:
   - Authorization: Bearer {{token}}
   - Content-Type: application/json
   ```

3. **Test Withdrawal**
   ```
   POST {{base_url}}/api/wallet-withdrawals
   Headers:
   - Authorization: Bearer {{token}}
   - Content-Type: application/json
   
   Body (raw JSON):
   {
     "amount": 5000,
     "otp_code": "123456"
   }
   ```

### Using Thunder Client or REST Client

```
### Send OTP
POST http://localhost/api/wallet-withdrawals/otp/send
Authorization: Bearer {{token}}

### Create Withdrawal
POST http://localhost/api/wallet-withdrawals
Authorization: Bearer {{token}}
Content-Type: application/json

{
  "amount": 5000,
  "otp_code": "123456"
}
```

---

**Last Updated:** 2026-06-19
**Version:** 1.0.0
