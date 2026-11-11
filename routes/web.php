<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
| Web Routes (Blade + session, guard 'web')
| - /            : APP_ENV=local -> chuyển hướng sang tài liệu API /docs/api;
|                  môi trường khác (docs bị Scramble chặn) -> trang chào
| - /login       : form đăng nhập quản trị (guest)
| - /dashboard   : trang quản trị (auth)
| - /logout      : đăng xuất (POST, có CSRF)
*/

Route::get('/', function () {
    // /docs/api chỉ mở khi APP_ENV=local (RestrictedDocsAccess, config/scramble.php),
    // nên chỉ chuyển hướng ở local để môi trường khác không rơi vào trang 403.
    if (app()->environment('local')) {
        return redirect('/docs/api');
    }

    return view('welcome');
})->name('home');

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
