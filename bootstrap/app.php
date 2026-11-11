<?php

use App\Exceptions\ApiExceptionHandler;
use App\Exceptions\NoContentException;
use App\Http\Middleware\IsWeb;
use App\Http\Middleware\LocalizationMiddleware;
use App\Http\Middleware\MultiAuthorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Khởi tạo ứng dụng
|--------------------------------------------------------------------------
| - Routing: web (Blade + session), api (prefix /api, Sanctum token), console.
| - Middleware: alias 'is-web' cho controller web; append Localization
|   (đọc header X-localization) và MultiAuthor (chọn guard đang đăng nhập).
| - Exceptions: mọi request /api/* được chuyển cho ApiExceptionHandler để
|   trả JSON thống nhất {status, message, errors?}.
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is-web' => IsWeb::class,
        ]);
        $middleware->append([
            LocalizationMiddleware::class,
            MultiAuthorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // NoContentException chỉ dùng để trả 204, không ghi log.
        $exceptions->dontReport(NoContentException::class);

        $exceptions->render(function (Throwable $th, Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return resolve(ApiExceptionHandler::class)->render($th);
            }
        });
    })->create();
