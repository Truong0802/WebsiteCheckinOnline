<?php

namespace App\Contracts\Services;

/**
 * Contract service xác thực API bằng JWT, hỗ trợ nhiều loại tài khoản.
 */
interface AuthenticationServiceInterface
{
    public function setDefaultModel(string $model);

    public function setMultiAuth(array $authModels);

    public function parseAuthType(array $parseData);

    public function attempt($credentials);

    public function register($requestData);

    public function login($credentials);

    public function refresh();

    public function logout();
}
