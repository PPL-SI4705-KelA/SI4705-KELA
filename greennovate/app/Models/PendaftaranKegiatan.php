<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PendaftaranKegiatan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_kegiatan';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'nama_lengkap',
        'no_hp',
        'alamat',
        'status',
        'qr_code',
        'dokumentasi',
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

    // ── Accessors ───────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Menunggu'     => 'Menunggu',
            'Dikonfirmasi' => 'Dikonfirmasi',
            'Ditolak'      => 'Ditolak',
            'Selesai'      => 'Selesai',
            default        => $this->status ?? 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Menunggu'     => 'yellow',
            'Dikonfirmasi' => 'blue',
            'Ditolak'      => 'red',
            'Selesai'      => 'green',
            default        => 'gray',
        };
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function hasDokumentasi(): bool
    {
        return $this->dokumentasi !== null && Storage::exists($this->dokumentasi);
    }

    public function hasQrCode(): bool
    {
        return $this->qr_code !== null;
    }
}
