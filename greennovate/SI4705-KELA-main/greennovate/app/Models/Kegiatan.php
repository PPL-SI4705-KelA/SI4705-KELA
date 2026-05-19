<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama',
        'slug',
        'lokasi_lahan_id',
        'petugas_id',
        'tanggal',
        'target_pohon',
        'realisasi_pohon',
        'quota',
        'registered_count',
        'status',
        'deskripsi',
        'terms',
        'image',
        'registration_open_at',
        'registration_close_at',
    ];

    protected $casts = [
        'tanggal'               => 'datetime',
        'registration_open_at'  => 'datetime',
        'registration_close_at' => 'datetime',
        'target_pohon'          => 'integer',
        'realisasi_pohon'       => 'integer',
        'quota'                 => 'integer',
        'registered_count'      => 'integer',
    ];

    // ── Boot: Auto-generate slug saat kegiatan dibuat ────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($kegiatan) {
            if (empty($kegiatan->slug)) {
                $kegiatan->slug = Str::slug($kegiatan->nama) . '-' . time();
            }
        });

        static::updating(function ($kegiatan) {
            if (empty($kegiatan->slug)) {
                $kegiatan->slug = Str::slug($kegiatan->nama) . '-' . $kegiatan->id;
            }
        });
    }

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function lokasiLahan()
    {
        return $this->belongsTo(LokasiLahan::class, 'lokasi_lahan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function realisasis()
    {
        return $this->hasMany(Realisasi::class, 'kegiatan_id');
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    public function hasPendaftar(): bool
    {
        return in_array($this->status, ['Berlangsung', 'Selesai']);
    }

    public function isAktif(): bool
    {
        return $this->status === 'Berlangsung';
    }

    public function isRegistrationOpen(): bool
    {
        $now = Carbon::now();

        if ($this->status !== 'Berlangsung') {
            return false;
        }

        if ($this->registration_open_at && $now->lessThan($this->registration_open_at)) {
            return false;
        }

        if ($this->registration_close_at && $now->greaterThan($this->registration_close_at)) {
            return false;
        }

        if ($this->quota > 0 && $this->registered_count >= $this->quota) {
            return false;
        }

        return true;
    }

    public function progressPercentage(): int
    {
        if ($this->quota <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->registered_count / $this->quota) * 100));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Persiapan'   => 'Persiapan',
            'Berlangsung' => 'Berlangsung',
            'Selesai'     => 'Selesai',
            'Dibatalkan'  => 'Dibatalkan',
            default       => $this->status ?? 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Persiapan'   => 'yellow',
            'Berlangsung' => 'green',
            'Selesai'     => 'gray',
            'Dibatalkan'  => 'red',
            default       => 'gray',
        };
    }

    public function getRegistrationDisabledReasonAttribute(): ?string
    {
        $now = Carbon::now();

        if ($this->status === 'Persiapan') {
            return 'Kegiatan ini belum dibuka untuk pendaftaran.';
        }

        if ($this->status === 'Selesai') {
            return 'Kegiatan ini telah selesai dilaksanakan.';
        }

        if ($this->status === 'Dibatalkan') {
            return 'Kegiatan ini telah dibatalkan.';
        }

        if ($this->registration_open_at && $now->lessThan($this->registration_open_at)) {
            return 'Pendaftaran belum dibuka. Dibuka pada '
                . $this->registration_open_at->translatedFormat('d F Y, H:i') . ' WIB.';
        }

        if ($this->registration_close_at && $now->greaterThan($this->registration_close_at)) {
            return 'Batas waktu pendaftaran telah berakhir.';
        }

        if ($this->quota > 0 && $this->registered_count >= $this->quota) {
            return 'Kuota peserta sudah penuh.';
        }

        return null;
    }

    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->quota - $this->registered_count);
    }
}