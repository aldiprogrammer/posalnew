<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('order_items', 'pembayaran')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->enum('pembayaran', ['tunai', 'transfer', 'qris'])->nullable()->change();
            });
        } else {
            Schema::table('order_items', function (Blueprint $table) {
                $table->enum('pembayaran', ['tunai', 'transfer', 'qris'])->nullable()->after('kasir_id');
            });
        }

        if (Schema::hasColumn('order', 'pembayaran')) {
            Schema::table('order', function (Blueprint $table) {
                $table->enum('pembayaran', ['tunai', 'transfer', 'qris'])->nullable()->change();
            });
        } else {
            Schema::table('order', function (Blueprint $table) {
                $table->enum('pembayaran', ['tunai', 'transfer', 'qris'])->nullable()->after('meja');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('order_items', 'pembayaran')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('pembayaran');
            });
        }

        if (Schema::hasColumn('order', 'pembayaran')) {
            Schema::table('order', function (Blueprint $table) {
                $table->dropColumn('pembayaran');
            });
        }
    }
};
