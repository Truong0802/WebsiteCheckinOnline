<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng nhóm hành động theo tài khoản-supplier (GroupAccountAction):
     * liên kết account_actions với supplier_config_accounts.
     */
    public function up(): void
    {
        Schema::create('group_account_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_action_id')->constrained('account_actions');
            $table->foreignId('supplier_config_account_id')->constrained('supplier_config_accounts');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng group_account_actions.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_account_actions');
    }
};
