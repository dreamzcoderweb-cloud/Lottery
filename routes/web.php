<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
Route::get('/', function () {
    return view('auth_login');
})->name('login');
Route::get('/server-commands/clear-cache', function () {

    // // Laravel caches
     Artisan::call('optimize:clear');

     // Spatie permission cache (IMPORTANT)
     Artisan::call('permission:cache-reset');

     return 'All caches cleared successfully!';
});
