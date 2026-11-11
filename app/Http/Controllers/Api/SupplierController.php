<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\SupplierServiceInterface;
use App\Http\Requests\Api\Supplier\StoreSupplierRequest;
use App\Http\Requests\Api\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API CRUD nhà cung cấp: /api/suppliers (cần JWT, group auth:api).
 * Controller mỏng: validate bằng FormRequest -> gọi SupplierServiceInterface -> trả SupplierResource.
 * {supplier} dùng route model binding; id không tồn tại => ModelNotFoundException => 404 JSON.
 */
class SupplierController extends Controller
{
    public function __construct(
        public SupplierServiceInterface $supplierService
    ) {}

    /**
     * Danh sách supplier có phân trang (20/trang).
     * Hỗ trợ ?filter[name]=, ?filter[code]=, ?filter[status]=, ?filter[type_access_id]=, ?sort=name|code|created_at|updated_at.
     */
    public function index(): AnonymousResourceCollection
    {
        return SupplierResource::collection($this->supplierService->getList());
    }

    /**
     * Tạo supplier mới (HTTP 201).
     */
    public function store(StoreSupplierRequest $request): JsonResource
    {
        return new SupplierResource($this->supplierService->store($request->validated()));
    }

    /**
     * Chi tiết một supplier.
     */
    public function show(Supplier $supplier): JsonResource
    {
        return new SupplierResource($supplier);
    }

    /**
     * Cập nhật supplier (PUT/PATCH, chỉ field gửi lên).
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResource
    {
        return new SupplierResource($this->supplierService->update($supplier, $request->validated()));
    }

    /**
     * Xóa supplier; đang được bảng khác tham chiếu thì trả 409.
     */
    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->supplierService->delete($supplier);

        return response()->json(['message' => __('message.supplier.deleted')]);
    }
}
