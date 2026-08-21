<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Tidak beroperasi apa-apa: kasir_id kini varchar nullable
     * tanpa foreign key (lihat create_pesanan_table dan
     * make_kasir_id_varchar_nullable_on_order_items_table).
     */
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
