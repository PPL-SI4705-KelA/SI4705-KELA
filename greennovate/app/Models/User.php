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
     * Konstanta role yang tersedia di sistem Greennovate.
     */
    const ROLE_USER    = 'user';
    const ROLE_ADMIN   = 'admin';
    const ROLE_PETUGAS = 'petugas';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
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
        'locale',
        'notif_email',
        'notif_push',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (misal: API response).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut ke tipe data yang sesuai.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'notif_email'       => 'boolean',
            'notif_push'        => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Helper Methods untuk Role (Penting untuk Middleware)
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
     * Berguna jika satu fitur bisa diakses beberapa role.
     */
    public function hasRole(array|string $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    // -------------------------------------------------------
    // Relationships untuk Riwayat Partisipasi (PB-11)
    // -------------------------------------------------------

    public function donasis()
    {
        return $this->hasMany(Donasi::class);
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function pendaftaranKegiatans()
    {
        return $this->hasMany(PendaftaranKegiatan::class);
    }
}