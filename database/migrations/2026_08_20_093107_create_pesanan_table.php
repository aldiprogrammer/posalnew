<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order');
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->decimal('harga', 12, 0)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('diskon', 12, 0)->default(0);
            $table->date('tanggal');
            $table->foreignId('kasir_id')->constrained('pegawai')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
