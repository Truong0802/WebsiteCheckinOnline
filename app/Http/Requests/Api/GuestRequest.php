<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest nền cho API public (không cần đăng nhập).
 * Với API cần đăng nhập, tạo lớp tương tự và kiểm tra auth()->check()/quyền trong authorize().
 */
abstract class GuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    abstract public function rules(): array;
}
