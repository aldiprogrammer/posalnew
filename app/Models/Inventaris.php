<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\InventarisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    /** @use HasFactory<InventarisFactory> */
    use HasFactory, HasStore;

    protected $table = 'inventaris';

    protected $fillable = [
        'id_store',
        'kode_barang',
        'nama_barang',
        'kondisi_barang',
        'tgl_masuk',
    ];

    protected function casts(): array
    {
        return [
            'tgl_masuk' => 'date',
        ];
    }
}
