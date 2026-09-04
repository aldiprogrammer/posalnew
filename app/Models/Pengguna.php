<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory, HasStore;

    protected $table = 'pengguna';

    protected $fillable = [
        'id_store',
        'nama',
        'username',
        'jabatan_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
