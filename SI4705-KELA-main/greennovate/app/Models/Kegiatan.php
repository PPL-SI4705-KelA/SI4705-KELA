<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama',
        'lokasi',
        'tanggal',
        'target_pohon',
        'kuota_total',
        'kuota_terisi',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Hitung sisa kuota yang tersedia.
     */
    public function getKuotaSisaAttribute(): int
    {
        return max(0, $this->kuota_total - $this->kuota_terisi);
    }

    /**
     * Scope filter berdasarkan lokasi.
     */
    public function scopeByLokasi($query, ?string $lokasi)
    {
        if ($lokasi) {
            return $query->where('lokasi', 'like', '%' . $lokasi . '%');
        }
        return $query;
    }

    /**
     * Scope filter berdasarkan tanggal (format: YYYY-MM-DD).
     */
    public function scopeByTanggal($query, ?string $tanggal)
    {
        if ($tanggal) {
            return $query->whereDate('tanggal', $tanggal);
        }
        return $query;
    }

    /**
     * Scope filter berdasarkan status.
     */
    public function scopeByStatus($query, ?string $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }
}
