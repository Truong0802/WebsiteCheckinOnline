<?php

namespace App\Providers;

use App\Contracts\Services\AuthenticationServiceInterface;
use App\Services\AuthenticationService;
use Illuminate\Support\ServiceProvider;

/**
 * Đăng ký service xác thực và các Policy (Gate::policy) của dự án.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bind interface -> implementation cho service xác thực API.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthenticationServiceInterface::class,
            AuthenticationService::class
        );
    }

    /**
     * Khai báo policy khi có module nghiệp vụ, ví dụ:
     * Gate::policy(\App\Models\Post::class, \App\Policies\PostPolicy::class);
     */
    public function boot(): void
    {
        //
    }
}
