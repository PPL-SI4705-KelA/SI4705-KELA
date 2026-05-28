<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'user_id',
        'nama_item',
        'jumlah_item',
        'total_harga',
        'status',
        'kode_transaksi',
        'qr_code',
        'bukti_dokumentasi',
        'catatan',
    ];

    protected $casts = [
        'total_harga'  => 'decimal:2',
        'jumlah_item'  => 'integer',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    /**
     * Cek apakah QR Code tersedia.
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
