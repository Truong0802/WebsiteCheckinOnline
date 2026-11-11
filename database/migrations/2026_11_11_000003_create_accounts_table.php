<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng tài khoản (Account) - tách riêng với bảng users mặc định của Laravel.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            // Lưu mật khẩu đã hash (bcrypt/argon2), không lưu plain text
            $table->string('password');
        });
    }

    /**
     * Xóa bảng accounts.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
