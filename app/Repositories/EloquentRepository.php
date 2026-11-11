<?php

namespace App\Repositories;

use App\Contracts\Repositories\EloquentRepositoryInterface;
use App\Repositories\Traits\HasFilters;
use App\Repositories\Traits\HasIncludes;
use App\Repositories\Traits\HasMakeBuilder;
use App\Repositories\Traits\HasSorts;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Repository nền tảng:
 * - Lớp con khai báo getModel() trả về class model.
 * - getWithPagination() dùng spatie/laravel-query-builder, đọc tham số
 *   ?filter[..]=, ?sort=, ?include=, ?fields[..]= từ request và chỉ cho
 *   phép các field nằm trong allow-list (HasFilters/HasSorts/HasIncludes).
 * - Lớp con có thể khai báo: $fields, $includes, $default_sort,
 *   canFilters(), canSorts() để mở rộng allow-list.
 */
abstract class EloquentRepository implements EloquentRepositoryInterface
{
    use HasFilters, HasIncludes, HasMakeBuilder, HasSorts;

    protected $_model;

    protected int $_paginate = 20;

    public function __construct()
    {
        $this->setModel();
    }

    private function setModel(): void
    {
        $this->_model = app()->make($this->getModel());
    }

    abstract public function getModel(): string;

    public function getWithPagination($query = null): LengthAwarePaginator
    {
        $paginationQuery = ! empty($query) ? $query : $this->makeQueryBuilder();

        // spatie v7 nhận tham số variadic nên cần spread mảng allow-list.
        return $paginationQuery
            ->defaultSort($this->getDefaultSort())
            ->allowedFields(...$this->getFields())
            ->allowedIncludes(...$this->getIncludeFields())
            ->allowedFilters(...$this->getAllowFilters())
            ->allowedSorts(...$this->getAllowSorts())
            ->paginate($this->_paginate);
    }

    public function store(array $data)
    {
        return $this->_model->create($data);
    }

    public function update($model, array $data)
    {
        $model->update($data);

        return $model;
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function findById($id)
    {
        return $this->_model->find($id);
    }
}
