<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

/**
 * Repository mẫu: khai báo model và allow-list filter/sort riêng.
 */
class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    public function getModel(): string
    {
        return User::class;
    }

    /** Cho phép ?filter[name]=...&filter[email]=... */
    public function canFilters(): array
    {
        return ['name', 'email'];
    }

    /** Cho phép ?sort=name hoặc ?sort=-name */
    public function canSorts(): array
    {
        return ['name'];
    }
}
