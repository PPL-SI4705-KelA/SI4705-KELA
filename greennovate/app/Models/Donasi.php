<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';

    protected $fillable = [
        'user_id',
        'jumlah',
        'metode_bayar',
        'kode_transaksi',
        'status',
        'pesan',
        'bukti_bayar',
        'catatan_admin',
        'confirmed_at',
    ];

    protected $casts = [
        'jumlah'       => 'decimal:2',
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
            'Gagal'      => 'Gagal',
            'Kadaluarsa' => 'Kadaluarsa',
            default      => $this->status ?? 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Pending'    => 'yellow',
            'Sukses'     => 'green',
            'Gagal'      => 'red',
            'Kadaluarsa' => 'gray',
            default      => 'gray',
        };
    }

    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
}
