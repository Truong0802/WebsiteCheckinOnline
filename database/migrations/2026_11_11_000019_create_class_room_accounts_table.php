<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng tài khoản tham gia môn học-lớp-niên khóa (ClassRoomAccount):
     * lưu tổng điểm, điểm checkin, số lần checkin và điểm bài tập đã tổng hợp.
     */
    public function up(): void
    {
        Schema::create('class_room_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('class_subject_year_id')->constrained('class_subject_years');
            $table->unsignedTinyInteger('type')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->decimal('total_grade', 8, 2)->default(0);
            $table->decimal('total_checkin_grade', 8, 2)->default(0);
            $table->unsignedInteger('total_checkin_count')->default(0);
            $table->decimal('total_work_grade', 8, 2)->default(0);
        });
    }

    /**
     * Xóa bảng class_room_accounts.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_room_accounts');
    }
};
