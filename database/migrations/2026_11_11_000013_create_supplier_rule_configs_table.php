<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng quy tắc của cấu hình supplier (SuplierRuleConfig trên ERD):
     * gắn supplier_config với đơn vị tính calc_unit.
     */
    public function up(): void
    {
        Schema::create('supplier_rule_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_config_id')->constrained('supplier_configs');
            $table->unsignedTinyInteger('unit_type')->nullable();
            $table->foreignId('calc_unit_id')->constrained('calc_units');
        });
    }

    /**
     * Xóa bảng supplier_rule_configs.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_rule_configs');
    }
};
