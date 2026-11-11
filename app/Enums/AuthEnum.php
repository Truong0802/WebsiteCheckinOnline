<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Loại tài khoản đăng nhập API (field account_type).
 * Thêm loại mới: khai báo hằng số + model trong AuthController::__construct()
 * + guard/provider tương ứng trong config/auth.php.
 */
final class AuthEnum extends Enum
{
    // Giá trị 1 giữ nguyên như trước (USER), nay trỏ tới bảng accounts.
    const ACCOUNT = 1;

    const ACCOUNT_NAME = 'account';

    public static function getTypes(): array
    {
        return [
            self::ACCOUNT,
        ];
    }
}
