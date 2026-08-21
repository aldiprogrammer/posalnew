<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order')->unique();
            $table->decimal('total_harga', 12, 0)->default(0);
            $table->decimal('diskon', 12, 0)->default(0);
            $table->foreignId('member_id')->nullable()->constrained('member')->nullOnDelete();
            $table->string('meja');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
