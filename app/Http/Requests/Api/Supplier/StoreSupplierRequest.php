<?php

namespace App\Http\Requests\Api\Supplier;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Body tạo supplier: name, code (duy nhất), base_location_x/y (tùy chọn, tối đa 8 chữ số thập phân
 * theo cột decimal(11,8)), status (tùy chọn, 0 = ngừng / 1 = hoạt động; không gửi thì mặc định 1),
 * time_limit_num (số nguyên >= 0), type_access_id (phải tồn tại trong type_accesses).
 * created_by/updated_by không nhận từ client.
 */
class StoreSupplierRequest extends FormRequest
{
    /**
     * Route đã nằm trong auth:api; kiểm tra lại guard JWT cho chắc chắn.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('suppliers', 'code')],
            'base_location_x' => ['nullable', 'numeric', 'decimal:0,8', 'between:-999.99999999,999.99999999'],
            'base_location_y' => ['nullable', 'numeric', 'decimal:0,8', 'between:-999.99999999,999.99999999'],
            'status' => ['sometimes', 'integer', Rule::in([Supplier::STATUS_INACTIVE, Supplier::STATUS_ACTIVE])],
            'time_limit_num' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'type_access_id' => ['nullable', 'integer', Rule::exists('type_accesses', 'id')],
        ];
    }
}
