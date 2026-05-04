<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'user_id',
        'nama_produk',
        'kategori',
        'jumlah_item',
        'harga_satuan',
        'total_harga',
        'metode_bayar',
        'kode_transaksi',
        'status',
        'qr_code',
        'dokumentasi',
        'bukti_bayar',
        'catatan_admin',
        'confirmed_at',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga'  => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ───────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Pending'    => 'Pending',
            'Sukses'     => 'Sukses',
            'Dikirim'    => 'Dikirim',
            'Selesai'    => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
            default      => $this->status ?? 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Pending'    => 'yellow',
            'Sukses'     => 'green',
            'Dikirim'    => 'blue',
            'Selesai'    => 'gray',
            'Dibatalkan' => 'red',
            default      => 'gray',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
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
