<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranKegiatan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_kegiatans';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'nama_lengkap',
        'no_hp',
        'alamat',
        'status',
        'qr_code',
        'bukti_dokumentasi',
        'sertifikat',
        'catatan',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    /**
     * Cek apakah QR Code tiket tersedia.
     */
    public function hasQrCode(): bool
    {
        return !empty($this->qr_code)
            && file_exists(storage_path('app/public/' . $this->qr_code));
    }

    /**
     * Cek apakah file dokumentasi tersedia di storage.
     */
    public function hasDokumentasi(): bool
    {
        return !empty($this->bukti_dokumentasi)
            && file_exists(storage_path('app/public/' . $this->bukti_dokumentasi));
    }

    /**
     * Cek apakah sertifikat tersedia.
     */
    public function hasSertifikat(): bool
    {
        return !empty($this->sertifikat)
            && file_exists(storage_path('app/public/' . $this->sertifikat));
    }

    /**
     * Label status yang ramah pengguna.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Terdaftar'  => 'Terdaftar',
            'Hadir'      => 'Hadir',
            'Selesai'    => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
            default      => $this->status ?? 'Tidak Diketahui',
        };
    }

    /**
     * Warna badge untuk status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Terdaftar'  => 'blue',
            'Hadir'      => 'green',
            'Selesai'    => 'gray',
            'Dibatalkan' => 'red',
            default      => 'gray',
        };
    }
}
