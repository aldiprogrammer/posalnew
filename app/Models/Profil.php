<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory, HasStore;

    protected $table = 'profil';

    protected $fillable = [
        'id_store',
        'nama_usaha',
        'logo',
        'nohp',
        'alamat',
    ];

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/'.$this->logo) : '';
    }
}
