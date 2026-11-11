<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\AuthEnum;
use App\Http\Requests\Api\GuestRequest;
use Illuminate\Validation\Rule;

/**
 * Body: username, password, account_type (xem AuthEnum).
 */
class LoginRequest extends GuestRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
            'account_type' => ['required', Rule::in(AuthEnum::getTypes())],
        ];
    }
}
