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
        'status',
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
        return $query->where('status', 'active');
    }

    /**
     * Scope: hanya jenis pohon tidak aktif (termasuk soft-deleted).
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active';
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
        return match ($this->status) {
            'active'   => 'Aktif',
            'inactive' => 'Tidak Aktif',
            default    => $this->status ?? '-',
        };
    }

    /**
     * Warna badge status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'green',
            'inactive' => 'red',
            default    => 'gray',
        };
    }
}
