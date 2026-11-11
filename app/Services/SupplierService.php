<?php

namespace App\Services;

use App\Contracts\Services\SupplierServiceInterface;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Luồng CRUD nhà cung cấp (route nằm trong auth:api):
 * 1. getList(): phân trang 20 bản ghi, đọc ?filter[..]= / ?sort= theo allow-list (spatie/laravel-query-builder).
 * 2. store(): tạo mới từ dữ liệu đã validate (lọc theo $fillable), gán created_by/updated_by = tài khoản đăng nhập.
 * 3. update(): cập nhật field gửi lên, gán updated_by = tài khoản đăng nhập.
 * 4. delete(): xóa cứng; nếu supplier đang được bảng khác tham chiếu (FK) thì trả 409, không xóa.
 */
class SupplierService implements SupplierServiceInterface
{
    /** Số bản ghi mỗi trang, giống EloquentRepository::$_paginate. */
    protected int $_paginate = 20;

    /** Guard JWT để lấy tài khoản đang đăng nhập cho created_by/updated_by. */
    protected string $_guard = 'api';

    /**
     * Danh sách có phân trang:
     * - ?filter[name]=, ?filter[code]= tìm gần đúng; ?filter[status]=, ?filter[type_access_id]= so khớp chính xác.
     * - ?sort=name|code|created_at|updated_at (thêm '-' để giảm dần); mặc định mới cập nhật trước.
     */
    public function getList(): LengthAwarePaginator
    {
        return QueryBuilder::for(Supplier::class)
            ->defaultSort('-updated_at')
            ->allowedFilters(
                'name',
                'code',
                AllowedFilter::exact('status'),
                AllowedFilter::exact('type_access_id'),
            )
            ->allowedSorts('name', 'code', 'created_at', 'updated_at')
            ->paginate($this->_paginate);
    }

    public function store(array $data): Supplier
    {
        $supplier = new Supplier($data);
        $supplier->created_by = auth($this->_guard)->id();
        $supplier->updated_by = auth($this->_guard)->id();
        $supplier->save();

        return $supplier;
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->fill($data);
        $supplier->updated_by = auth($this->_guard)->id();
        $supplier->save();

        return $supplier;
    }

    /**
     * SQLSTATE 23000 = vi phạm ràng buộc toàn vẹn (supplier đang được class_rooms, supplier_configs... tham chiếu).
     * Lỗi DB khác vẫn ném tiếp để ApiExceptionHandler trả 500.
     */
    public function delete(Supplier $supplier): void
    {
        try {
            $supplier->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                throw new ConflictHttpException(__('message.supplier.in_use'), $e);
            }

            throw $e;
        }
    }
}
