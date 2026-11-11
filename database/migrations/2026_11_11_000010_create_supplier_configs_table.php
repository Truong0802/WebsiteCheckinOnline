<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng cấu hình của supplier (SuplierConfig trên ERD), được grade_logs
     * và supplier_rule_configs tham chiếu.
     */
    public function up(): void
    {
        Schema::create('supplier_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->string('name');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng supplier_configs.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_configs');
    }
};
