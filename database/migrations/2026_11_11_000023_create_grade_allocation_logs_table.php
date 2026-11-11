<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng lịch sử phân bổ điểm (GradeAllocationLog) cho class_room_account.
     */
    public function up(): void
    {
        Schema::create('grade_allocation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_account_id')->constrained('class_room_accounts');
            $table->date('start_date')->nullable();
            $table->unsignedTinyInteger('type_grade')->nullable();
            $table->decimal('amount', 8, 2)->default(0);
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng grade_allocation_logs.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_allocation_logs');
    }
};
