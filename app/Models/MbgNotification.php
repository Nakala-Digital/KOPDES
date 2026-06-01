<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbgNotification extends Model
{
    protected $fillable = [
        'mbg_order_id',
        'supplier_confirmation_id',
        'recipient_type',
        'recipient_name',
        'channel',
        'title',
        'message',
        'send_at',
    ];

    protected function casts(): array
    {
        return ['send_at' => 'datetime'];
    }
}
