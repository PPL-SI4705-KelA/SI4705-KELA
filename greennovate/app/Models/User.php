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

    /**
     * Konstanta role yang tersedia di sistem.
     */
    const ROLE_USER    = 'user';
    const ROLE_ADMIN   = 'admin';
    const ROLE_PETUGAS = 'petugas';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'city',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Helper methods untuk pengecekan role
    // Digunakan di: middleware, blade @if, unit test, dll.
    // -------------------------------------------------------

    /** Cek apakah user adalah Admin. */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Cek apakah user adalah Petugas. */
    public function isPetugas(): bool
    {
        return $this->role === self::ROLE_PETUGAS;
    }

    /** Cek apakah user adalah User biasa. */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Cek apakah user memiliki salah satu dari role yang diberikan.
     * Contoh: $user->hasRole(['admin', 'petugas'])
     */
    public function hasRole(array|string $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }
}
