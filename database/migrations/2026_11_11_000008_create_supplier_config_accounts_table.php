<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng gán tài khoản vào supplier (SupplierConfigAccount).
     */
    public function up(): void
    {
        Schema::create('supplier_config_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->unsignedTinyInteger('status')->default(1);
        });
    }

    /**
     * Xóa bảng supplier_config_accounts.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_config_accounts');
    }
};
