<?php

namespace App\Contracts\Repositories;

/**
 * Repository mẫu cho model User; module mới tạo interface tương tự rồi
 * RepositoryServiceProvider sẽ tự bind theo tên.
 */
interface UserRepositoryInterface extends EloquentRepositoryInterface {}
