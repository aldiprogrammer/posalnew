<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('potongan_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('member')->cascadeOnDelete();
            $table->enum('jenis', ['diskon', 'rupiah']);
            $table->decimal('nominal', 12, 0)->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('potongan_member');
    }
};
