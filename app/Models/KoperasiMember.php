<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KoperasiMember extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
        'member_number',
        'name',
        'phone',
        'nik',
        'village_name',
        'address',
        'status',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
        ];
    }

    public function savings(): HasMany
    {
        return $this->hasMany(KoperasiSaving::class, 'member_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(KoperasiLoan::class, 'member_id');
    }
}
