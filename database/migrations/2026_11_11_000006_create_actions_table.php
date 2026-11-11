<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng danh mục hành động/quyền (Action).
     */
    public function up(): void
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('action_code', 100)->unique();
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng actions.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
