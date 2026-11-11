<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng biểu thức/công thức tính (CalcRegex).
     */
    public function up(): void
    {
        Schema::create('calc_regexes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 100)->unique();
        });
    }

    /**
     * Xóa bảng calc_regexes.
     */
    public function down(): void
    {
        Schema::dropIfExists('calc_regexes');
    }
};
