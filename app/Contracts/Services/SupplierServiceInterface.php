<?php

namespace App\Contracts\Services;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Nghiệp vụ CRUD nhà cung cấp (bảng suppliers); bind -> App\Services\SupplierService trong AppServiceProvider.
 */
interface SupplierServiceInterface
{
    public function getList(): LengthAwarePaginator;

    public function store(array $data): Supplier;

    public function update(Supplier $supplier, array $data): Supplier;

    public function delete(Supplier $supplier): void;
}
