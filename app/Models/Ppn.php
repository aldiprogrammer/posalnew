<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\PpnFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
{
    /** @use HasFactory<PpnFactory> */
    use HasFactory, HasStore;

    protected $table = 'ppn';

    protected $fillable = [
        'id_store',
        'persentase',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'persentase' => 'float',
            'aktif' => 'boolean',
        ];
    }

    public function getPersentaseFormattedAttribute(): string
    {
        $value = rtrim(rtrim(number_format($this->persentase, 2, ',', '.'), '0'), ',');

        return $value.'%';
    }
}
