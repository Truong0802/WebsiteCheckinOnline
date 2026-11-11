<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng môn học của lớp (ClassRoomSubject): N-N giữa class_rooms và subjects, có thời gian hiệu lực.
     */
    public function up(): void
    {
        Schema::create('class_room_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('class_room_id')->constrained('class_rooms');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    /**
     * Xóa bảng class_room_subjects.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_room_subjects');
    }
};
