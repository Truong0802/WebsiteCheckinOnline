<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng nhà cung cấp/đơn vị (Supplier): lưu tọa độ gốc dùng đối chiếu vị trí checkin,
     * giới hạn thời gian và loại quyền truy cập.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            // Tọa độ gốc, độ chính xác 8 chữ số thập phân (đủ cho kinh độ/vĩ độ)
            $table->decimal('base_location_x', 11, 8)->nullable();
            $table->decimal('base_location_y', 11, 8)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedInteger('time_limit_num')->nullable();
            $table->foreignId('type_access_id')->nullable()->constrained('type_accesses');
        });
    }

    /**
     * Xóa bảng suppliers.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
