<?php

namespace App\Models;

use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * Tài khoản đăng nhập mặc định (bảng accounts) cho cả web (session, guard 'web')
 * và API (JWT, guard 'api'). Đăng nhập bằng username; chỉ status = STATUS_ACTIVE được phép.
 */
class Account extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<AccountFactory> */
    use HasFactory, Notifiable;

    /** Trạng thái hoạt động, trùng giá trị default của cột accounts.status. */
    public const STATUS_ACTIVE = 1;

    /**
     * Field cho phép gán hàng loạt; status, created_by, updated_by không nhận từ request.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'phone',
        'email',
        'password',
    ];

    /**
     * Giá trị mặc định khi tạo mới, khớp default của DB để model trả về có sẵn status.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'password' => 'hashed',
        ];
    }

    /**
     * Claim 'sub' của JWT = khóa chính accounts.id.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Không thêm claim riêng; guard dùng 'prv' (lock_subject) để chặn token của model khác.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
