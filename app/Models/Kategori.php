<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\KategoriFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /** @use HasFactory<KategoriFactory> */
    use HasFactory, HasStore;

    protected $table = 'kategori';

    protected $fillable = [
        'id_store',
        'nama',
        'keterangan',
    ];
}
