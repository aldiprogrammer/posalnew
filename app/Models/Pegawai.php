<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\PegawaiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    /** @use HasFactory<PegawaiFactory> */
    use HasFactory, HasStore;

    protected $table = 'pegawai';

    protected $fillable = [
        'id_store',
        'nama',
        'jabatan',
        'no_wa',
        'alamat',
        'jenis_kelamin',
    ];
}
