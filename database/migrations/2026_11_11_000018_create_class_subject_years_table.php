<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng môn học của lớp theo niên khóa (ClassSubjectYear).
     */
    public function up(): void
    {
        Schema::create('class_subject_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_subject_id')->constrained('class_room_subjects');
            $table->foreignId('class_year_id')->constrained('class_years');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng class_subject_years.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject_years');
    }
};
