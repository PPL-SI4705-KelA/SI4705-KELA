<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;

    protected $table = 'realisasis';

    protected $fillable = [
        'kegiatan_id',
        'petugas_id',
        'jenis_pohon_id',
        'jumlah',
        'catatan',
        'recorded_at',
    ];

    protected $casts = [
        'jumlah'      => 'integer',
        'recorded_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function jenisPohon()
    {
        return $this->belongsTo(JenisPohon::class, 'jenis_pohon_id');
    }
}
