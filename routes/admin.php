<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BannerControllerFilter;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\WalletWithdrawalAdminController;
use App\Http\Controllers\WalletRechargeAdminController;
use Illuminate\Support\Facades\Route;

// login route
//Route::view('/', 'auth_login');
Route::match(['get', 'post'], 'login', [AuthController::class, 'login'])->name('login.check');
Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout']);

Route::middleware(['auth', 'auth.session'])->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    // profile
    Route::get('profile', [ProfileController::class, 'show'])
        ->middleware('permission:profile.view')
        ->name('profile.show');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])
        ->middleware('permission:profile.password')
        ->name('profile.password');


    // banner routes (with filter)
    Route::get('banners', [BannerControllerFilter::class, 'index'])
        ->middleware('permission:banners.view')
        ->name('banners.index');
    Route::match(['get', 'post'], 'add_banner', [BannerControllerFilter::class, 'add'])->middleware('permission:banners.create');
    Route::match(['get', 'post'], 'edit_banner/{id}', [BannerControllerFilter::class, 'update'])->middleware('permission:banners.edit');
    Route::post('delete_banner/{id}', [BannerControllerFilter::class, 'delete'])->middleware('permission:banners.delete');
    Route::match(['get', 'post'], 'change_banner_status', [BannerControllerFilter::class, 'change_status'])->middleware('permission:banners.edit');
    Route::match(['get', 'post'], 'banner_status', [BannerControllerFilter::class, 'banner_status'])->middleware('permission:banners.view');

    // Role routes
    Route::get('roles_with_filter', [RoleController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('roles.index');
    Route::match(['get', 'post'], 'add_role', [RoleController::class, 'add'])->middleware('permission:roles.create');
    Route::match(['get', 'post'], 'edit_role/{id}', [RoleController::class, 'update'])->middleware('permission:roles.edit');
    Route::post('delete_role/{id}', [RoleController::class, 'delete'])->middleware('permission:roles.delete');

    // Staff routes
    Route::get('staff', [StaffController::class, 'index'])
        ->middleware('permission:staff.view')
        ->name('staff.index');
    Route::match(['get', 'post'], 'add_staff', [StaffController::class, 'add'])->middleware('permission:staff.create');
    Route::match(['get', 'post'], 'edit_staff/{id}', [StaffController::class, 'update'])->middleware('permission:staff.edit');
    Route::post('delete_staff/{id}', [StaffController::class, 'delete'])->middleware('permission:staff.delete');

    //Customer routes
    Route::get('customers', [CustomerController::class, 'index'])
        ->middleware('permission:customers.view')
        ->name('customers.index');
    Route::get('customers/{id}', [CustomerController::class, 'show'])
        ->middleware('permission:customers.show')
        ->name('customers.show');
    // removed: profile / other modules

    // Slot routes
    Route::get('slots', [SlotController::class, 'index'])
        ->middleware('permission:slots.view')
        ->name('slots.index');
    Route::match(['get', 'post'], 'add_slot', [SlotController::class, 'add'])
        ->middleware('permission:slots.create')
        ->name('slots.add');
    Route::match(['get', 'post'], 'edit_slot/{slug}', [SlotController::class, 'update'])
        ->middleware('permission:slots.edit')
        ->name('slots.edit');
    Route::post('delete_slot/{slug}', [SlotController::class, 'delete'])
        ->middleware('permission:slots.delete')
        ->name('slots.delete');
    Route::post('delete_slot_item/{id}', [SlotController::class, 'deleteItem'])
        ->middleware('permission:slots.edit')
        ->name('slots.items.delete');

    // Wallet withdrawals (Admin Panel)
    Route::get('wallet-withdrawals', [WalletWithdrawalAdminController::class, 'index'])
        ->middleware('permission:withdrawals.view')
        ->name('withdrawals.index');
    Route::get('wallet-withdrawals/{id}', [WalletWithdrawalAdminController::class, 'show'])
        ->middleware('permission:withdrawals.view')
        ->name('withdrawals.show');
    Route::post('wallet-withdrawals/{id}/approve', [WalletWithdrawalAdminController::class, 'approve'])
        ->middleware('permission:withdrawals.approve')
        ->name('withdrawals.approve');
    Route::post('wallet-withdrawals/{id}/reject', [WalletWithdrawalAdminController::class, 'reject'])
        ->middleware('permission:withdrawals.reject')
        ->name('withdrawals.reject');

    // Wallet recharges (Admin Panel)
    Route::get('wallet-recharges', [WalletRechargeAdminController::class, 'index'])
        ->middleware('permission:recharges.view')
        ->name('recharges.index');
    Route::get('wallet-recharges/{id}', [WalletRechargeAdminController::class, 'show'])
        ->middleware('permission:recharges.view')
        ->name('recharges.show');
    Route::post('wallet-recharges/{id}/approve', [WalletRechargeAdminController::class, 'approve'])
        ->middleware('permission:recharges.approve')
        ->name('recharges.approve');
    Route::post('wallet-recharges/{id}/reject', [WalletRechargeAdminController::class, 'reject'])
        ->middleware('permission:recharges.reject')
        ->name('recharges.reject');

    Route::get('reports/winnings-slots', [ReportController::class, 'winningsSlotsReport'])
        ->middleware('permission:reports.winningsslots')
        ->name('reports.winningsslots');

    Route::get('reports/winnings-slots/{slot_id}', [ReportController::class, 'slotCustomerDetails'])
        ->middleware('permission:reports.winningsslots')
        ->name('reports.slot-details');
});
