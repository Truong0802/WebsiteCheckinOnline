<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng tọa độ của lần checkin (CheckinLocation), thuộc checkin_logs.
     */
    public function up(): void
    {
        Schema::create('checkin_locations', function (Blueprint $table) {
            $table->id();
            $table->decimal('x_log', 11, 8)->nullable();
            $table->decimal('y_log', 11, 8)->nullable();
            $table->foreignId('checkin_log_id')->constrained('checkin_logs');
        });
    }

    /**
     * Xóa bảng checkin_locations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_locations');
    }
};
