<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'kode_order',
        'total_harga',
        'diskon',
        'member_id',
        'meja',
        'tanggal',
        'status_cetak',
    ];

    protected $casts = [
        'total_harga' => 'integer',
        'diskon' => 'integer',
        'tanggal' => 'date',
        'status_cetak' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'kode_order', 'kode_order');
    }

    public function getJumlahItemAttribute(): int
    {
        return (int) ($this->attributes['jumlah_item'] ?? $this->items->sum('qty'));
    }

    public function getTotalHargaFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->total_harga, 0, ',', '.');
    }

    public function getDiskonFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->diskon, 0, ',', '.');
    }
}
