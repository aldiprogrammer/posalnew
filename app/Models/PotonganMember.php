<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\PotonganMemberFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganMember extends Model
{
    /** @use HasFactory<PotonganMemberFactory> */
    use HasFactory, HasStore;

    protected $table = 'potongan_member';

    protected $fillable = [
        'id_store',
        'jenis',
        'nominal',
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_akhir' => 'date',
        ];
    }

    public function getNominalFormattedAttribute(): string
    {
        if ($this->jenis === 'diskon') {
            return $this->nominal.'%';
        }

        return 'Rp '.number_format($this->nominal, 0, ',', '.');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('tanggal_mulai', '<=', now())
            ->where('tanggal_akhir', '>=', now());
    }
}
