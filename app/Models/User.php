<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'status',
        'is_premium',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_premium' => 'boolean',
        ];
    }

    // Auto-generate UUIDs on creating
    protected static function booted()
    {
    static::creating(function ($user) {
        if (!$user->id) {
            $user->id = (string) Str::uuid();
        }
    });
}

    /* =========================
       Role Helpers
    ========================= */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
    public function isPremium(): bool
    {
        return $this->is_premium;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    public function sendPasswordResetNotification($token)
    {
    $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
    }

    /* =========================
       Relationships
    ========================= */

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }
}