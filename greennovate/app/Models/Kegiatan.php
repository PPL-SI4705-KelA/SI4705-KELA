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
        'lokasi_lahan_id',
        'petugas_id',
        'tanggal',
        'target_pohon',
        'realisasi_pohon',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function lokasiLahan()
    {
        return $this->belongsTo(LokasiLahan::class, 'lokasi_lahan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
