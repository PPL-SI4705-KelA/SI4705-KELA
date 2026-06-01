<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiKegiatan extends Model
{
    protected $table = 'dokumentasi_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'petugas_id',
        'foto'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class,'petugas_id');
    }
}