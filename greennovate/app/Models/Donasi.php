<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasis';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'nama_donasi',
        'jumlah',
        'metode_pembayaran',
        'status',
        'kode_transaksi',
        'bukti_dokumentasi',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
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
     * Cek apakah file dokumentasi tersedia di storage.
     */
    public function hasDokumentasi(): bool
    {
        return !empty($this->bukti_dokumentasi)
            && file_exists(storage_path('app/public/' . $this->bukti_dokumentasi));
    }

    /**
     * Label status yang ramah pengguna.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'Menunggu',
            'Sukses'  => 'Sukses',
            'Gagal'   => 'Gagal',
            'Expired' => 'Kedaluwarsa',
            default   => $this->status ?? 'Tidak Diketahui',
        };
    }

    /**
     * Warna badge untuk status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'yellow',
            'Sukses'  => 'green',
            'Gagal'   => 'red',
            'Expired' => 'gray',
            default   => 'gray',
        };
    }
}
