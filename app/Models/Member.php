<?php

namespace App\Models;

use App\Models\Concerns\HasStore;
use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function potonganMembers(): HasMany
    {
        return $this->hasMany(PotonganMember::class);
    }
}
