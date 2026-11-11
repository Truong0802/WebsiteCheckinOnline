<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Tạo tài khoản mẫu trong bảng accounts: username 'admin' / mật khẩu 'password' (chỉ dùng dev).
     * firstOrCreate để chạy db:seed nhiều lần không lỗi trùng username.
     */
    public function run(): void
    {
        Account::query()->firstOrCreate(
            ['username' => 'admin'],
            Account::factory()->raw(['username' => 'admin', 'email' => 'admin@example.com']),
        );
    }
}
