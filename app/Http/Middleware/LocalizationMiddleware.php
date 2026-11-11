<?php

namespace App\Http\Middleware;

use App\Enums\LanguageEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Đặt ngôn ngữ theo header X-localization (vi|en|ja).
 * Giá trị không hợp lệ hoặc không gửi => giữ APP_LOCALE.
 */
class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $language = $request->header('X-localization');

        if (! empty($language) && LanguageEnum::hasValue($language)) {
            app()->setLocale($language);
        }

        return $next($request);
    }
}
