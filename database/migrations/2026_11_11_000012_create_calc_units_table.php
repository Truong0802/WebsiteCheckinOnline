<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng đơn vị tính (CalcUnit), mỗi đơn vị gắn với một calc_regex.
     */
    public function up(): void
    {
        Schema::create('calc_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // ERD chưa chỉ rõ kiểu của value nên lưu dạng chuỗi
            $table->string('value')->nullable();
            $table->foreignId('calc_regex_id')->constrained('calc_regexes');
            $table->unsignedTinyInteger('type')->nullable();
        });
    }

    /**
     * Xóa bảng calc_units.
     */
    public function down(): void
    {
        Schema::dropIfExists('calc_units');
    }
};
