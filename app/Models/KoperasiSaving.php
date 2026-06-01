<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KoperasiSaving extends Model
{
    protected $fillable = [
        'member_id',
        'created_by',
        'type',
        'amount',
        'paid_at',
        'period',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(KoperasiMember::class, 'member_id');
    }
}
