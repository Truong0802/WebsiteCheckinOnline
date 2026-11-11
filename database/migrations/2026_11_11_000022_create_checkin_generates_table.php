<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng mã QR checkin được sinh (CheckinGenerate) bởi tài khoản cho một supplier,
     * gồm thời điểm sinh (date_gen + time_gen) và giờ hết hạn end_time.
     */
    public function up(): void
    {
        Schema::create('checkin_generates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->string('qr_url', 500)->nullable();
            $table->text('qr_string')->nullable();
            $table->time('time_gen')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->unsignedTinyInteger('status')->default(1);
            $table->time('end_time')->nullable();
            $table->date('date_gen')->nullable();
        });
    }

    /**
     * Xóa bảng checkin_generates.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_generates');
    }
};
