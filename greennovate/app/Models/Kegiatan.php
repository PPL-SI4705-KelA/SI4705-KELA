<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama',
        'lokasi_lahan_id',
        'petugas_id',
        'tanggal',
        'target_pohon',
        'realisasi_pohon',
        'status',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'         => 'date',
            'target_pohon'    => 'integer',
            'realisasi_pohon' => 'integer',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────────────────

    public function lokasLahan()
    {
        return $this->belongsTo(LokasLahan::class, 'lokasi_lahan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    public function hasPendaftar(): bool
    {
        return in_array($this->status, ['Berlangsung', 'Selesai']);
    }

    public function isAktif(): bool
    {
        return $this->status === 'Berlangsung';
    }
}
