<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng loại quyền truy cập (TypeAccess), được suppliers tham chiếu qua type_access_id.
     */
    public function up(): void
    {
        Schema::create('type_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng type_accesses.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_accesses');
    }
};
