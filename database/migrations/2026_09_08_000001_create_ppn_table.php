<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel PPN (Pajak Pertambahan Nilai) per store.
     * id_store varchar = (string) Auth::id() (id user yang login), diisi otomatis oleh trait HasStore.
     */
    public function up(): void
    {
        Schema::create('ppn', function (Blueprint $table) {
            $table->id();
            $table->string('id_store')->nullable()->index();
            $table->decimal('persentase', 5, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppn');
    }
};
