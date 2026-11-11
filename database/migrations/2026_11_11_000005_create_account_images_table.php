<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng ảnh của tài khoản (AccountImage), một tài khoản có nhiều ảnh.
     */
    public function up(): void
    {
        Schema::create('account_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_url', 500);
            $table->foreignId('account_id')->constrained('accounts');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng account_images.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_images');
    }
};
