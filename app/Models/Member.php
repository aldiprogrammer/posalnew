<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory, HasStore;

    protected $table = 'member';

    protected $fillable = [
        'id_store',
        'nama',
        'nik',
        'alamat',
        'tanggal_bergabung',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bergabung' => 'date',
        ];
    }
}
