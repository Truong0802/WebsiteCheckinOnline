<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contract chung cho mọi Repository Eloquent (CRUD + phân trang có filter/sort).
 */
interface EloquentRepositoryInterface
{
    public function getWithPagination($query = null): LengthAwarePaginator;

    public function store(array $data);

    public function update($model, array $data);

    public function delete($model);

    public function findById($id);
}
