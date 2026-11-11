<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng lịch sử checkin (CheckinLog) của một class_room_account:
     * ngày, giờ, trình duyệt và IP lúc checkin.
     */
    public function up(): void
    {
        Schema::create('checkin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_account_id')->constrained('class_room_accounts');
            $table->date('date_checkin');
            $table->time('checkin_time');
            $table->string('browser_checkin', 500)->nullable();
            // 45 ký tự đủ chứa địa chỉ IPv6
            $table->string('ip_checkin', 45)->nullable();
        });
    }

    /**
     * Xóa bảng checkin_logs.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_logs');
    }
};
