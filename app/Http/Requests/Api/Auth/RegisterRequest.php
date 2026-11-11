<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\AuthEnum;
use App\Http\Requests\Api\GuestRequest;
use Illuminate\Validation\Rule;

/**
 * Body: username (duy nhất trong accounts), email (tùy chọn, duy nhất), phone (tùy chọn),
 * password, confirm_password, account_type.
 * validated() chỉ trả field đã khai báo; service tiếp tục lọc theo $fillable của Account.
 */
class RegisterRequest extends GuestRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:100', Rule::unique('accounts', 'username')],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('accounts', 'email')],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'same:password'],
            'account_type' => ['required', Rule::in(AuthEnum::getTypes())],
        ];
    }
}
