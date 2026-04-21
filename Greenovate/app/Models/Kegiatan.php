<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    /** @use HasFactory<\Database\Factories\KegiatanFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan', 
        'lokasi', 
        'tanggal', 
        'target_pohon', 
        'kuota_tersisa', 
        'status'
    ];
}
