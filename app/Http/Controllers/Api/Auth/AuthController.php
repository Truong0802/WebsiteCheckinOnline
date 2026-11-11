<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Services\AuthenticationServiceInterface;
use App\Enums\AuthEnum;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API xác thực: /api/auth/{login,register,refresh,profile,logout}.
 * Controller mỏng: validate bằng FormRequest -> gọi AuthenticationService -> trả Resource/JSON.
 */
class AuthController extends Controller
{
    public function __construct(
        public AuthenticationServiceInterface $authService
    ) {
        // Map account_type -> model; thêm loại tài khoản mới tại đây.
        $this->authService->setDefaultModel(model: Account::class);
        $this->authService->setMultiAuth([
            AuthEnum::ACCOUNT => Account::class,
        ]);
    }

    /**
     * Đăng ký tài khoản mới trong bảng accounts.
     */
    public function register(RegisterRequest $request): JsonResource
    {
        $account = $this->authService->register($request->validated());

        return new AuthResource($account);
    }

    /**
     * Đăng nhập bằng username/password, trả JWT (token_type, access_token, expired_at).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request->validated());
    }

    /**
     * Đổi JWT còn hiệu lực lấy JWT mới; token cũ không dùng lại được.
     */
    public function refresh(): JsonResponse
    {
        return $this->authService->refresh();
    }

    /**
     * Đăng xuất: thu hồi JWT đang dùng.
     */
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    /**
     * Thông tin tài khoản đang đăng nhập (theo Bearer token).
     * Chỉ định guard 'api' (JWT) để không phụ thuộc guard mặc định ('web') do middleware đặt.
     */
    public function profile(): JsonResource
    {
        return new AuthResource(auth('api')->user());
    }
}
