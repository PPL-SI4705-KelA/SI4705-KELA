<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPohon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_pohons';

    protected $fillable = [
        'nama',
        'nama_latin',
        'kategori_pohon_id',
        'harga',
        'created_by',
        'version',
    ];

    protected $casts = [
        'harga'   => 'decimal:2',
        'version' => 'integer',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function kategori()
    {
        return $this->belongsTo(KategoriPohon::class, 'kategori_pohon_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Format harga ke Rupiah: "Rp 50.000"
     */
    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float)($this->harga ?? 0), 0, ',', '.');
    }
}
