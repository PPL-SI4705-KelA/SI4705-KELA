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
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ----------------------------------------------------------------
    // Role helpers
    // ----------------------------------------------------------------

    /** Daftar role yang valid. */
    public const ROLES = ['user', 'admin', 'petugas'];

    /** Apakah pengguna adalah admin? */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Apakah pengguna adalah petugas? */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /** Apakah pengguna adalah user biasa? */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Kembalikan nama route dashboard sesuai role.
     */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin'   => 'admin.dashboard',
            'petugas' => 'petugas.dashboard',
            default   => 'user.dashboard',
        };
    }
}