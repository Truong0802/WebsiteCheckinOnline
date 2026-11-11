<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm remember_token cho accounts để đăng nhập web (session) dùng được "Ghi nhớ đăng nhập".
     * Tách migration riêng, không sửa migration tạo bảng đã có.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->rememberToken()->after('password');
        });
    }

    /**
     * Gỡ cột remember_token.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
