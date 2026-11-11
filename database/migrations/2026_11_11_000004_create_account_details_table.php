<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng thông tin chi tiết tài khoản (AccountDetail), quan hệ 1-1 với accounts:
     * account_id vừa là khóa chính vừa là khóa ngoại.
     */
    public function up(): void
    {
        Schema::create('account_details', function (Blueprint $table) {
            $table->foreignId('account_id')->primary()->constrained('accounts');
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->text('address_text')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng account_details.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_details');
    }
};
