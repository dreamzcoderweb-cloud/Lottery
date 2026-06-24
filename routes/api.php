<?php

use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\SlotController;
use App\Http\Controllers\Api\WalletRechargeController;
use App\Http\Controllers\Api\WalletWithdrawalController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\BannerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('customer/register', [CustomerAuthController::class, 'register']);
    Route::post('customer/login', [CustomerAuthController::class, 'login']);
    Route::post('check-mobile', [CustomerAuthController::class, 'checkMobile']);
    Route::post('check-details', [CustomerAuthController::class, 'checkDetails']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('customer/me', [CustomerAuthController::class, 'me']);
        Route::post('customer/logout', [CustomerAuthController::class, 'logout']);
        Route::get('banner', [BannerController::class, 'index']);
        Route::prefix('slot')->group(function () {
            Route::get('/', [SlotController::class, 'index']);
            Route::get('sub-slot/{id}', [SlotController::class, 'show']);
        });
        Route::get('wallet-recharges',[WalletRechargeController::class, 'index']);
        Route::post('wallet-recharge/store', [WalletRechargeController::class, 'store']);
        Route::post('bookings', [BookingController::class, 'store']);
        Route::get('tickets/bookings', [BookingController::class, 'index']);
        Route::get('tickets/result', [BookingController::class, 'result']);
        Route::get('bank', [BankController::class, 'index']);
        Route::post('bank-accounts', [BankController::class, 'addBankAccount']);

        // Wallet withdrawals (Customer)
        Route::get('wallet-withdrawals/validate', [WalletWithdrawalController::class, 'validateBalance']);
        Route::post('wallet-withdrawals/otp/send', [WalletWithdrawalController::class, 'sendOtp']);
        Route::get('wallet-withdrawals', [WalletWithdrawalController::class, 'index']);
        Route::post('wallet-withdrawals', [WalletWithdrawalController::class, 'store']);
        Route::get('wallet-withdrawals/{id}', [WalletWithdrawalController::class, 'show']);

        Route::prefix('reports')->group(function () {
            Route::get('winning-slots', [ReportController::class, 'slotWinningReport']);
            Route::get('customer', [ReportController::class, 'customerTickets']);
        });
    });
});
