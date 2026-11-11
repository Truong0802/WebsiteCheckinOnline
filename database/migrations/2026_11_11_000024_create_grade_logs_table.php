<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng lịch sử điểm (GradeLog) của class_room_account theo cấu hình supplier.
     */
    public function up(): void
    {
        Schema::create('grade_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_account_id')->constrained('class_room_accounts');
            $table->foreignId('supplier_config_id')->constrained('supplier_configs');
            $table->unsignedTinyInteger('type_grade')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng grade_logs.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_logs');
    }
};
