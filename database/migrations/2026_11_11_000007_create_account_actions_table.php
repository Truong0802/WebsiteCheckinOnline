<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng gán hành động cho tài khoản (AccountAction): N-N giữa accounts và actions.
     */
    public function up(): void
    {
        Schema::create('account_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_id')->constrained('actions');
            $table->foreignId('account_id')->constrained('accounts');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng account_actions.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_actions');
    }
};
