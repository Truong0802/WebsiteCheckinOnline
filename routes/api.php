<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| API Routes (prefix /api)
|--------------------------------------------------------------------------
| - Public: khai báo trực tiếp (Route::resource(...)->only([...])).
| - Cần đăng nhập: đặt trong group auth:api (JWT, header Authorization: Bearer {token}).
| - Lỗi được ApiExceptionHandler chuyển thành JSON {status, message, errors?}.
*/

Route::get('/', fn () => throw new NotFoundHttpException);

Route::middleware(['auth:api'])->group(function () {
    // Module nghiệp vụ cần đăng nhập, ví dụ:
    // Route::apiResource('post', App\Http\Controllers\Api\PostController::class);
});

require __DIR__.'/auth.php';
