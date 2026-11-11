<?php

namespace App\Http\Resources\Auth;

use App\Enums\AuthEnum;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Dữ liệu tài khoản trả về client; không bao gồm password/remember_token.
 * Tag mixin giúp Scramble suy ra kiểu field khi sinh tài liệu API (/docs/api).
 *
 * @mixin Account
 */
class AuthResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            ...$this->getAuthInfo(),
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Thêm account_type theo class model; mở rộng khi có thêm loại tài khoản.
     */
    private function getAuthInfo(): array
    {
        return match (true) {
            $this->resource instanceof Account => ['account_type' => AuthEnum::ACCOUNT_NAME],
            default => [],
        };
    }
}
