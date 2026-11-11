<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng niên khóa (ClassYear), ví dụ 2026-2027.
     */
    public function up(): void
    {
        Schema::create('class_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('from_year');
            $table->unsignedSmallInteger('to_year');
            $table->string('name');
        });
    }

    /**
     * Xóa bảng class_years.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_years');
    }
};
