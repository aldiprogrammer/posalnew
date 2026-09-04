<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    /** @use HasFactory<ProdukFactory> */
    use HasFactory, HasStore;

    protected $table = 'produk';

    protected $fillable = [
        'id_store',
        'kategori_id',
        'nama',
        'foto',
        'keterangan',
        'harga',
        'diskon',
        'stok',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'diskon' => 'integer',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }

    public function getDiskonFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->diskon, 0, ',', '.');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }
}
