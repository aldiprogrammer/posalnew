<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel yang mendapat kolom id_store (varchar, nullable).
     * Ketika create, trait HasStore akan otomatis isi dengan Auth::id().
     */
    private array $tables = [
        'kategori',
        'produk',
        'pegawai',
        'member',
        'pengguna',
        'order',
        'order_items',
        'profil',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) {
                if (! Schema::hasColumn($t->getTable(), 'id_store')) {
                    $t->string('id_store')->nullable()->after('id')->index();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) {
                if (Schema::hasColumn($t->getTable(), 'id_store')) {
                    $t->dropColumn('id_store');
                }
            });
        }
    }
};
