<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramDonasi extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'program_donasis';

    // Kolom yang diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'nama_program',
        'deskripsi',
        'target_dana',
        'dana_terkumpul',
        'status_aktif', // true = tampil di aplikasi, false = disembunyikan/ditutup
    ];

    // Mengubah tipe data saat diakses di aplikasi
    protected $casts = [
        'target_dana'    => 'decimal:2',
        'dana_terkumpul' => 'decimal:2',
        'status_aktif'   => 'boolean',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    /**
     * Relasi One-to-Many: Satu program penghijauan memiliki banyak transaksi donasi.
     */
    public function donasis(): HasMany
    {
        return $this->hasMany(Donasi::class, 'program_donasi_id');
    }

    // ── Helper / Accessor ───────────────────────────────────────────────────

    /**
     * Menghitung persentase capaian donasi secara otomatis.
     * Contoh penggunaan di Blade: $program->persentase_capaian
     */
    public function getPersentaseCapaianAttribute(): int
    {
        if ($this->target_dana <= 0) {
            return 0;
        }

        $persen = ($this->dana_terkumpul / $this->target_dana) * 100;
        
        return $persen > 100 ? 100 : (int) $persen;
    }
}