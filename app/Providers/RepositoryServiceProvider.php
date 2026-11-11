<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Tự động bind Repository theo quy ước tên file:
 *   app/Repositories/{Model}Repository.php
 *   => App\Contracts\Repositories\{Model}RepositoryInterface
 * Lớp abstract EloquentRepository được bỏ qua vì không có interface riêng để bind.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach (scandir(app_path('Repositories')) as $file) {
            if (! str_ends_with($file, 'Repository.php') || $file === 'EloquentRepository.php') {
                continue;
            }

            $model = str_replace('Repository.php', '', $file);
            $this->app->bind(
                "App\\Contracts\\Repositories\\{$model}RepositoryInterface",
                "App\\Repositories\\{$model}Repository"
            );
        }
    }

    public function boot(): void
    {
        //
    }
}
