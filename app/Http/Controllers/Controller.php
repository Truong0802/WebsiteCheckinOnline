<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;

/**
 * Controller nền cho trang web (Blade): luôn chạy middleware 'is-web' để dùng guard session.
 */
abstract class Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['is-web'];
    }
}
