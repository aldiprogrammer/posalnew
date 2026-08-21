<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->dropKasirForeignKeys();

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('kasir_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('kasir_id')->nullable(false)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('kasir_id')->references('id')->on('pengguna')->cascadeOnDelete();
        });
    }

    private function dropKasirForeignKeys(): void
    {
        if ($this->isSqlite()) {
            try {
                Schema::table('order_items', function (Blueprint $table) {
                    $table->dropForeign(['kasir_id']);
                });
            } catch (QueryException) {
                // Foreign key tidak ada, lanjutkan.
            }

            return;
        }

        foreach (['order_items_kasir_id_foreign', 'pesanan_kasir_id_foreign'] as $name) {
            try {
                Schema::table('order_items', function (Blueprint $table) use ($name) {
                    $table->dropForeign($name);
                });
            } catch (QueryException) {
                // Foreign key tidak ada, lanjutkan.
            }
        }
    }

    private function isSqlite(): bool
    {
        return Schema::getConnection()->getDriverName() === 'sqlite';
    }
};
