<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPohon extends Model
{
    use HasFactory;

    protected $table = 'kategori_pohons';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function jenisPohons()
    {
        return $this->hasMany(JenisPohon::class, 'kategori_pohon_id');
    }
}
