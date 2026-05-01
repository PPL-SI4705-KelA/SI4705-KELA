<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'lokasi',
        'tanggal',
        'deskripsi',
        'target',
        'kuota',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'target'  => 'integer',
            'kuota'   => 'integer',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────────────────

    /**
     * Kegiatan bisa punya banyak pendaftar (untuk sprint berikutnya).
     * Relasi ini dipakai untuk mengecek apakah boleh dihapus.
     */
    // public function pendaftars()
    // {
    //     return $this->hasMany(Pendaftar::class);
    // }

    // ── Helper ────────────────────────────────────────────────────────────────

    /**
     * Cek apakah kegiatan ini sudah punya pendaftar.
     * Saat ini belum ada tabel pendaftar, selalu false.
     * Ganti implementasi saat tabel pendaftar sudah ada.
     */
    public function hasPendaftar(): bool
    {
        // return $this->pendaftars()->exists();
        return false;
    }

    /**
     * Apakah kegiatan masih aktif (bisa didaftari).
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /** Hanya kegiatan aktif. */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /** Hanya kegiatan yang belum lewat tanggal. */
    public function scopeMendatang($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString());
    }
}
