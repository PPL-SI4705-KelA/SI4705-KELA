<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    /** Notifikasi yang belum dibaca. */
    public function scopeBelumDibaca($query)
    {
        return $query->whereRaw('"is_read" IS FALSE');
    }

    /** Notifikasi yang sudah dibaca. */
    public function scopeSudahDibaca($query)
    {
        return $query->whereRaw('"is_read" IS TRUE');
    }

    // -------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------

    /**
     * Tandai notifikasi ini sebagai sudah dibaca.
     */
    public function tandaiDibaca(): void
    {
        if (! $this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Label ikon berdasarkan tipe notifikasi.
     */
    public function ikonTipe(): string
    {
        return match ($this->tipe) {
            'donasi'     => '💚',
            'pembayaran' => '💳',
            'kegiatan'   => '🌳',
            'sistem'     => '🔔',
            default      => '🔔',
        };
    }

    /**
     * Warna badge berdasarkan tipe notifikasi (Tailwind classes).
     */
    public function warnaTipe(): string
    {
        return match ($this->tipe) {
            'donasi'     => 'bg-green-100 text-green-700',
            'pembayaran' => 'bg-blue-100 text-blue-700',
            'kegiatan'   => 'bg-emerald-100 text-emerald-700',
            'sistem'     => 'bg-gray-100 text-gray-600',
            default      => 'bg-gray-100 text-gray-600',
        };
    }
}
