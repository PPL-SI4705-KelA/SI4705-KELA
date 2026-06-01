<?php

namespace App\Helpers;

/**
 * RiwayatMapper
 *
 * Helper class untuk menyatukan berbagai status dari tabel yang berbeda
 * (donasi, pembelian, pendaftaran kegiatan) ke dalam satu format riwayat
 * yang konsisten.
 */
class RiwayatMapper
{
    /**
     * Map status dari database ke label yang ramah pengguna.
     * Menyatukan berbagai format status dari tabel berbeda.
     *
     * @param  string|int|null  $status  Status mentah dari database
     * @param  string           $tipe    Tipe riwayat: 'donasi', 'pembelian', 'kegiatan'
     * @return string  Label status yang ramah pengguna
     */
    public static function statusMapper(string|int|null $status, string $tipe = 'donasi'): string
    {
        // Handle integer-based status (legacy/alternative DB schema)
        if (is_int($status) || is_numeric($status)) {
            return match ((int) $status) {
                0 => 'Menunggu',
                1 => 'Sukses',
                2 => 'Gagal',
                3 => 'Kedaluwarsa',
                4 => 'Selesai',
                5 => 'Dibatalkan',
                default => 'Tidak Diketahui',
            };
        }

        // Handle null
        if ($status === null) {
            return 'Tidak Diketahui';
        }

        // Handle string-based status per tipe
        return match ($tipe) {
            'donasi', 'pembelian' => match ($status) {
                'Pending'  => 'Menunggu',
                'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                'Ditolak'  => 'Ditolak',
                'Sukses'   => 'Sukses',
                'Gagal'    => 'Gagal',
                'Expired'  => 'Kedaluwarsa',
                default    => $status,
            },
            'kegiatan' => match ($status) {
                'Terdaftar'  => 'Terdaftar',
                'Hadir'      => 'Hadir',
                'Selesai'    => 'Selesai',
                'Dibatalkan' => 'Dibatalkan',
                default      => $status,
            },
            default => $status,
        };
    }

    /**
     * Map status ke warna CSS badge.
     *
     * @param  string  $statusLabel  Label status hasil dari statusMapper()
     * @return string  Nama warna untuk badge styling
     */
    public static function statusColor(string $statusLabel): string
    {
        return match ($statusLabel) {
            'Menunggu'     => 'yellow',
            'Menunggu Konfirmasi' => 'blue',
            'Ditolak'      => 'red',
            'Sukses'       => 'green',
            'Gagal'        => 'red',
            'Kedaluwarsa'  => 'gray',
            'Terdaftar'    => 'blue',
            'Hadir'        => 'green',
            'Selesai'      => 'emerald',
            'Dibatalkan'   => 'red',
            default        => 'gray',
        };
    }

    /**
     * Map tipe riwayat ke label yang ramah pengguna.
     *
     * @param  string  $tipe  Tipe riwayat: 'donasi', 'pembelian', 'kegiatan'
     * @return string  Label tipe yang ramah pengguna
     */
    public static function tipeLabel(string $tipe): string
    {
        return match ($tipe) {
            'donasi'    => 'Donasi',
            'pembelian' => 'Pembelian',
            'kegiatan'  => 'Kegiatan',
            default     => ucfirst($tipe),
        };
    }

    /**
     * Map tipe riwayat ke ikon SVG class.
     *
     * @param  string  $tipe
     * @return string  Warna untuk ikon
     */
    public static function tipeColor(string $tipe): string
    {
        return match ($tipe) {
            'donasi'    => 'rose',
            'pembelian' => 'blue',
            'kegiatan'  => 'green',
            default     => 'gray',
        };
    }
}
