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
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_wa', 20)->nullable()->after('email');
            $table->string('nama_store')->nullable()->after('no_wa');
            $table->string('jenis_usaha', 50)->nullable()->after('nama_store');
            $table->text('alamat')->nullable()->after('jenis_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_wa', 'nama_store', 'jenis_usaha', 'alamat']);
        });
    }
};
