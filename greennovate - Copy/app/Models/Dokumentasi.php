<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'petugas_id',
        'file_path',
    ];

    /**
     * Kegiatan terkait dari dokumentasi ini.
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    /**
     * Petugas yang mengunggah dokumentasi.
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
