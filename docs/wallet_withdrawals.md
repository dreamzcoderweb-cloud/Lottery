# Wallet Withdrawals (API + Backend)

## DB schema

Migration: `database/migrations/2026_05_25_000001_create_wallet_withdrawals_table.php`

Table: `wallet_withdrawals`

- `wallet_withdrawal_id` (PK)
- `customer_id`
- `amount`
- `status` (`pending`, `approved`, `rejected`)
- `remarks` (admin/user remarks)
- `created_at`, `updated_at`

## Config

File: `config/wallet.php`

Environment keys (see `.env.example`):
- `WALLET_WITHDRAW_DEDUCT_ON` (`request` or `approval`)
- `WALLET_WITHDRAW_MIN`, `WALLET_WITHDRAW_MAX`
- OTP: `WALLET_WITHDRAW_OTP_ENABLED`, `WALLET_WITHDRAW_OTP_TTL`

## Model relationships

- `App\Models\Customer::walletWithdrawals()`
- `App\Models\WalletWithdrawal::customer()`
- `App\Models\WalletWithdrawal::processedBy()`

## API endpoints

Customer (auth: sanctum):
- `GET /api/v1/wallet-withdrawals/validate` (balance + limits)
- `POST /api/v1/wallet-withdrawals/otp/send` (OTP scaffold)
- `POST /api/v1/wallet-withdrawals` (create request)
- `GET /api/v1/wallet-withdrawals` (history)
- `GET /api/v1/wallet-withdrawals/{id}` (details)

Admin (auth: sanctum + `api_admin` middleware):
- `GET /api/v1/admin/wallet-withdrawals?status=pending|approved|rejected`
- `GET /api/v1/admin/wallet-withdrawals/{id}`
- `POST /api/v1/admin/wallet-withdrawals/{id}/approve`
- `POST /api/v1/admin/wallet-withdrawals/{id}/reject` (requires `remarks`)
 

## Transaction safety

Implementation: `app/Services/WalletWithdrawalService.php`

- All state changes run inside `DB::transaction()`.
- Wallet row uses `lockForUpdate()` to prevent double-spend.
- Debit happens on `request` or `approval` based on `WALLET_WITHDRAW_DEDUCT_ON`.
- If debited on request, a rejection issues an automatic refund (credit) + wallet transaction entry.

## Security best practices checklist (recommended next steps)

- Store bank details only as needed; consider encrypting `account_number`/`ifsc_code` at rest.
- Add idempotency key for `POST /wallet-withdrawals` to prevent duplicate submissions.
- Add rate limiting (`throttle`) on OTP + withdrawal creation.
- Add device/IP logging and configurable “cooldown” between withdrawals.
- Consider a separate “available_balance” vs “ledger_balance” model for pending holds.
- Integrate real OTP delivery (SMS/Email) and never return OTP in responses in production.
