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
     * Scope: hanya jenis pohon aktif.
     */
    public function scopeActive($query)
    {
        return $query; // soft delete automatically handles excluding inactive
    }

    /**
     * Scope: hanya jenis pohon tidak aktif (termasuk soft-deleted).
     */
    public function scopeInactive($query)
    {
        return $query->onlyTrashed();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return !$this->trashed();
    }

    /**
     * Format harga ke Rupiah: "Rp 50.000"
     */
    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float)($this->harga ?? 0), 0, ',', '.');
    }

    /**
     * Label status yang user-friendly.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->trashed() ? 'Tidak Aktif' : 'Aktif';
    }

    /**
     * Warna badge status.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->trashed() ? 'red' : 'green';
    }
}
