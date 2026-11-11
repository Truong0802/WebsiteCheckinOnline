<?php

namespace App\Http\Requests\Api\Supplier;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Body cập nhật supplier (PUT/PATCH): cùng rule với StoreSupplierRequest nhưng mọi field là 'sometimes'
 * (chỉ validate và cập nhật field có gửi lên); code duy nhất, bỏ qua chính bản ghi đang sửa;
 * status chỉ nhận 0/1, không gửi thì giữ nguyên.
 */
class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => [
                'sometimes', 'required', 'string', 'max:50',
                Rule::unique('suppliers', 'code')->ignore($this->route('supplier')),
            ],
            'base_location_x' => ['sometimes', 'nullable', 'numeric', 'decimal:0,8', 'between:-999.99999999,999.99999999'],
            'base_location_y' => ['sometimes', 'nullable', 'numeric', 'decimal:0,8', 'between:-999.99999999,999.99999999'],
            'status' => ['sometimes', 'integer', Rule::in([Supplier::STATUS_INACTIVE, Supplier::STATUS_ACTIVE])],
            'time_limit_num' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:4294967295'],
            'type_access_id' => ['sometimes', 'nullable', 'integer', Rule::exists('type_accesses', 'id')],
        ];
    }
}
