<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'quota',
        'registered_count',
        'terms',
        'image',
        'start_date',
        'end_date',
        'registration_open_at',
        'registration_close_at',
        'status',
    ];

    protected $casts = [
        'start_date'             => 'datetime',
        'end_date'               => 'datetime',
        'registration_open_at'   => 'datetime',
        'registration_close_at'  => 'datetime',
    ];

    /**
     * Cek apakah pendaftaran masih bisa dilakukan.
     * Pendaftaran terbuka jika:
     *  - Status masih 'open'
     *  - Sekarang berada dalam rentang registration_open_at – registration_close_at
     *  - Kuota belum penuh
     */
    public function isRegistrationOpen(): bool
    {
        $now = Carbon::now();

        return $this->status === 'open'
            && $now->greaterThanOrEqualTo($this->registration_open_at)
            && $now->lessThanOrEqualTo($this->registration_close_at)
            && $this->registered_count < $this->quota;
    }

    /**
     * Persentase kuota terisi (0–100).
     */
    public function progressPercentage(): int
    {
        if ($this->quota <= 0) {
            return 100;
        }

        return (int) min(100, round(($this->registered_count / $this->quota) * 100));
    }

    /**
     * Label status untuk tampilan.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open'      => 'Pendaftaran Dibuka',
            'closed'    => 'Pendaftaran Ditutup',
            'completed' => 'Kegiatan Selesai',
            default     => 'Tidak Diketahui',
        };
    }

    /**
     * Warna badge status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open'      => 'green',
            'closed'    => 'red',
            'completed' => 'gray',
            default     => 'gray',
        };
    }

    /**
     * Alasan tombol daftar dinonaktifkan (null = bisa mendaftar).
     */
    public function getRegistrationDisabledReasonAttribute(): ?string
    {
        $now = Carbon::now();

        if ($this->status === 'completed') {
            return 'Kegiatan ini telah selesai dilaksanakan.';
        }

        if ($this->status === 'closed') {
            return 'Pendaftaran untuk kegiatan ini telah ditutup.';
        }

        if ($now->lessThan($this->registration_open_at)) {
            return 'Pendaftaran belum dibuka. Dibuka pada ' . $this->registration_open_at->translatedFormat('d F Y, H:i') . ' WIB.';
        }

        if ($now->greaterThan($this->registration_close_at)) {
            return 'Batas waktu pendaftaran telah berakhir.';
        }

        if ($this->registered_count >= $this->quota) {
            return 'Kuota peserta sudah penuh.';
        }

        return null;
    }

    /**
     * Sisa kuota peserta.
     */
    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->quota - $this->registered_count);
    }
}
