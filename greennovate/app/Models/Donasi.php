<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasis';

    protected $fillable = [
        'user_id',
        'kegiatan_id',

        'nama_donasi',
        'nama_donatur',
        'nomor_hp',

        'jumlah',
        'metode_pembayaran',

        'status',
        'kode_transaksi',

        'bukti_dokumentasi',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(
            Kegiatan::class,
            'kegiatan_id'
        );
    }

    public function hasDokumentasi(): bool
    {
        return !empty($this->bukti_dokumentasi)
            && file_exists(
                storage_path(
                    'app/public/' . $this->bukti_dokumentasi
                )
            );
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'Pending' => 'Menunggu Pembayaran',

            'Sukses' => 'Donasi Berhasil',

            'Gagal' => 'Donasi Gagal',

            'Expired' => 'Kedaluwarsa',

            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {

            'Pending' => 'yellow',

            'Sukses' => 'green',

            'Gagal' => 'red',

            'Expired' => 'gray',

            default => 'gray',
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isSuccess(): bool
    {
        return $this->status === 'Sukses';
    }

    public function isFailed(): bool
    {
        return $this->status === 'Gagal';
    }

    public function isExpired(): bool
    {
        return $this->status === 'Expired';
    }

    public function isUploadCompleted(): bool
    {
        return !empty($this->bukti_dokumentasi);
    }

    /**
     * Cek apakah transaksi sudah melewati batas pembayaran 10 menit
     */
    public function isPaymentExpired(): bool
    {
        return $this->created_at
            ->copy()
            ->addMinutes(10)
            ->isPast();
    }

    /**
     * Sisa waktu pembayaran dalam menit
     */
    public function getRemainingMinutesAttribute(): int
    {
        $expiredAt = $this->created_at
            ->copy()
            ->addMinutes(10);

        if ($expiredAt->isPast()) {
            return 0;
        }

        return now()->diffInMinutes(
            $expiredAt,
            false
        );
    }
}