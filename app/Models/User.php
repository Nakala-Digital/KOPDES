<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pin_hash',
        'role_id',
        'phone',
        'nik',
        'status',
        'failed_login_attempts',
        'locked_until',
        'last_login_at',
        'remember_refresh_token',
        'remember_refresh_token_expires_at',
        'must_change_pin',
        'village_name',
    ];

    protected $hidden = [
        'password',
        'pin_hash',
        'remember_token',
        'remember_refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime',
            'last_login_at' => 'datetime',
            'remember_refresh_token_expires_at' => 'datetime',
            'must_change_pin' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
