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
        Schema::table('order', function (Blueprint $table) {
            if (Schema::hasColumn('order', 'uang')) {
                $table->decimal('uang', 12, 0)->nullable()->change();
            } else {
                $table->decimal('uang', 12, 0)->nullable()->after('pembayaran');
            }

            if (Schema::hasColumn('order', 'kembalian')) {
                $table->decimal('kembalian', 12, 0)->nullable()->change();
            } else {
                $table->decimal('kembalian', 12, 0)->nullable()->after('uang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('order', 'kembalian') && Schema::getColumnType('order', 'kembalian') === 'decimal') {
            Schema::table('order', function (Blueprint $table) {
                $table->dropColumn(['uang', 'kembalian']);
            });
        }
    }
};
