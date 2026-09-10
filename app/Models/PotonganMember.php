<?php

namespace App\Models;

use Database\Factories\PotonganMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganMember extends Model
{
    /** @use HasFactory<PotonganMemberFactory> */
    use HasFactory;

    protected $table = 'potongan_member';

    protected $fillable = [
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
}
