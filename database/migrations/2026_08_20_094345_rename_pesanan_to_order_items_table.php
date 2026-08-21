<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('pesanan', 'order_items');
    }

    public function down(): void
    {
        Schema::rename('order_items', 'pesanan');
    }
};
