<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ép guard mặc định = 'web' (session) cho các controller trang quản trị Blade.
 */
class IsWeb
{
    public function handle(Request $request, Closure $next): Response
    {
        Auth::setDefaultDriver('web');

        return $next($request);
    }
}
