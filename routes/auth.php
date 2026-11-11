<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
| API xác thực: /api/auth/*
| login/register giới hạn 10 request/phút/IP để chống dò mật khẩu.
| Các route còn lại dùng guard 'api' (JWT): header Authorization: Bearer {access_token}.
| refresh cần token còn hạn; hết hạn thì đăng nhập lại.
*/
Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('login', 'login')->name('login');
        Route::post('register', 'register')->name('register');
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('refresh', 'refresh')->name('refresh');
        Route::post('logout', 'logout')->name('logout');
    });
});
