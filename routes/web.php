<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
| Web Routes (Blade + session, guard 'web')
| - /            : trang chào
| - /login       : form đăng nhập quản trị (guest)
| - /dashboard   : trang quản trị (auth)
| - /logout      : đăng xuất (POST, có CSRF)
*/

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Module quản trị, ví dụ:
    // Route::resource('post', App\Http\Controllers\PostController::class);
});
