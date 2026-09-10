<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\MejaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    /** @use HasFactory<MejaFactory> */
    use HasFactory, HasStore;

    protected $table = 'meja';

    protected $fillable = [
        'id_store',
        'no_meja',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
}
