<?php

namespace App\Services;

use App\Contracts\Services\AuthenticationServiceInterface;
use App\Models\Account;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

/**
 * Luồng xác thực API bằng JWT (guard 'api' trong config/auth.php):
 * 1. Controller gọi setMultiAuth([account_type => ModelClass]).
 * 2. parseAuthType() đọc account_type từ request để chọn model; sai loại => 401.
 * 3. login(): tìm theo username + status hoạt động, kiểm tra Hash, cấp JWT (Bearer).
 * 4. register(): tạo bản ghi ở model tương ứng (password được cast 'hashed').
 * 5. refresh(): đổi token đang dùng lấy token mới, token cũ bị đưa vào blacklist.
 * 6. logout(): đưa token hiện tại vào blacklist (cache), token không dùng lại được.
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    protected $_model;

    protected array $_multiAuth = [];

    /** Guard JWT dùng để cấp/thu hồi token. */
    protected string $_guard = 'api';

    private function setModel(string $model): void
    {
        $this->_model = app()->make($model);
    }

    public function setDefaultModel(string $model): void
    {
        $this->setModel($model);
    }

    public function setMultiAuth(array $authModels): void
    {
        $this->_multiAuth = $authModels;
    }

    public function parseAuthType(array $parseData): string
    {
        $accountType = data_get($parseData, 'account_type', '');
        $authType = data_get($this->_multiAuth, $accountType, '');

        if (empty($authType)) {
            throw new AuthenticationException;
        }

        return $authType;
    }

    /**
     * Kiểm tra username/password của tài khoản đang hoạt động; đúng thì cấp JWT.
     * Sai thông tin hoặc tài khoản bị khóa đều trả 401 như nhau (không tiết lộ lý do).
     */
    public function attempt($credentials): array
    {
        $account = $this->_model
            ->where('username', data_get($credentials, 'username', ''))
            ->where('status', Account::STATUS_ACTIVE)
            ->first();

        if (! empty($account) && Hash::check(data_get($credentials, 'password', ''), $account->password)) {
            $ttl = config('jwt.ttl');

            return [
                'token_type' => 'Bearer',
                'access_token' => auth($this->_guard)->login($account),
                'expired_at' => $ttl ? now()->addMinutes($ttl)->format('Y-m-d H:i:s') : null,
            ];
        }

        throw new AuthenticationException;
    }

    public function register($requestData): Model
    {
        $this->setModel($this->parseAuthType($requestData));

        // Chỉ lấy các field cho phép theo $fillable của model.
        return $this->_model->create($requestData);
    }

    public function login($credentials): JsonResponse
    {
        $this->setModel($this->parseAuthType($credentials));

        return response()->json($this->attempt($credentials));
    }

    /**
     * Cấp token mới cho token còn hiệu lực (route nằm trong auth:api); token cũ vào blacklist.
     * Lỗi JWT (hết hạn, sai chữ ký, đã blacklist) chuyển thành 401 theo format chung.
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = auth($this->_guard)->refresh();
        } catch (JWTException) {
            throw new AuthenticationException;
        }

        $ttl = config('jwt.ttl');

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expired_at' => $ttl ? now()->addMinutes($ttl)->format('Y-m-d H:i:s') : null,
        ]);
    }

    public function logout(): JsonResponse
    {
        auth($this->_guard)->logout();

        return response()->json(['message' => __('message.logout_success')]);
    }
}
