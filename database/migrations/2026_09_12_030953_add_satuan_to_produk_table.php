<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->string('satuan_besar')->nullable()->after('stok');
            $table->unsignedInteger('isi')->nullable()->after('satuan_besar');
            $table->unsignedInteger('qty')->nullable()->after('isi');
            $table->unsignedBigInteger('harga_satuan_besar')->nullable()->after('qty');
            $table->string('satuan_kecil')->nullable()->after('harga_satuan_besar');
            $table->unsignedInteger('qty_all')->nullable()->after('satuan_kecil');
            $table->unsignedBigInteger('harga_satuan_kecil')->nullable()->after('qty_all');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn([
                'satuan_besar',
                'isi',
                'qty',
                'harga_satuan_besar',
                'satuan_kecil',
                'qty_all',
                'harga_satuan_kecil',
            ]);
        });
    }
};
