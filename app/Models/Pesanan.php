<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    use HasFactory, HasStore;

    protected $table = 'order_items';

    protected $fillable = [
        'id_store',
        'kode_order',
        'produk_id',
        'harga',
        'qty',
        'satuan',
        'diskon',
        'tanggal',
        'kasir_id',
        'pembayaran',
    ];

    protected $casts = [
        'harga' => 'integer',
        'diskon' => 'integer',
        'tanggal' => 'date',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'kasir_id');
    }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }

    public function getDiskonFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->diskon, 0, ',', '.');
    }

    public function getTotalAttribute(): int
    {
        return ($this->harga * $this->qty) - $this->diskon;
    }

    public function getTotalFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->total, 0, ',', '.');
    }
}
