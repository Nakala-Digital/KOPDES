<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmNotification extends Model
{
    protected $fillable = [
        'umkm_profile_id',
        'order_id',
        'recipient_type',
        'recipient_name',
        'channel',
        'title',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
