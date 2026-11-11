<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Duyệt các guard trong config/auth.php, guard nào đang đăng nhập thì đặt làm
 * guard mặc định để auth()->user() trả đúng loại tài khoản.
 */
class MultiAuthorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $guards = array_keys(config('auth.guards'));
        $currentGuard = collect($guards)->first(fn ($guard) => auth()->guard($guard)->check());

        if (! empty($currentGuard)) {
            Auth::setDefaultDriver($currentGuard);
        }

        return $next($request);
    }
}
