<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng môn học (Subject).
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng subjects.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
