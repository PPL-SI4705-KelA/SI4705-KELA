<?php

namespace Tests\Unit;

use App\Helpers\RiwayatMapper;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test untuk RiwayatMapper::statusMapper()
 * Memastikan fungsi mengubah integer/string DB menjadi label status yang tepat.
 */
class RiwayatMapperTest extends TestCase
{
    // ── Integer-based status mapping ────────────────────────────────────────

    public function test_integer_0_maps_to_menunggu(): void
    {
        $this->assertEquals('Menunggu', RiwayatMapper::statusMapper(0));
    }

    public function test_integer_1_maps_to_sukses(): void
    {
        $this->assertEquals('Sukses', RiwayatMapper::statusMapper(1));
    }

    public function test_integer_2_maps_to_gagal(): void
    {
        $this->assertEquals('Gagal', RiwayatMapper::statusMapper(2));
    }

    public function test_integer_3_maps_to_kedaluwarsa(): void
    {
        $this->assertEquals('Kedaluwarsa', RiwayatMapper::statusMapper(3));
    }

    public function test_integer_4_maps_to_selesai(): void
    {
        $this->assertEquals('Selesai', RiwayatMapper::statusMapper(4));
    }

    public function test_integer_5_maps_to_dibatalkan(): void
    {
        $this->assertEquals('Dibatalkan', RiwayatMapper::statusMapper(5));
    }

    public function test_unknown_integer_maps_to_tidak_diketahui(): void
    {
        $this->assertEquals('Tidak Diketahui', RiwayatMapper::statusMapper(99));
    }

    // ── String-based status mapping (donasi/pembelian) ─────────────────────

    public function test_pending_donasi_maps_to_menunggu(): void
    {
        $this->assertEquals('Menunggu', RiwayatMapper::statusMapper('Pending', 'donasi'));
    }

    public function test_sukses_donasi_maps_to_sukses(): void
    {
        $this->assertEquals('Sukses', RiwayatMapper::statusMapper('Sukses', 'donasi'));
    }

    public function test_expired_pembelian_maps_to_kedaluwarsa(): void
    {
        $this->assertEquals('Kedaluwarsa', RiwayatMapper::statusMapper('Expired', 'pembelian'));
    }

    // ── String-based status mapping (kegiatan) ─────────────────────────────

    public function test_terdaftar_kegiatan(): void
    {
        $this->assertEquals('Terdaftar', RiwayatMapper::statusMapper('Terdaftar', 'kegiatan'));
    }

    public function test_hadir_kegiatan(): void
    {
        $this->assertEquals('Hadir', RiwayatMapper::statusMapper('Hadir', 'kegiatan'));
    }

    public function test_selesai_kegiatan(): void
    {
        $this->assertEquals('Selesai', RiwayatMapper::statusMapper('Selesai', 'kegiatan'));
    }

    public function test_dibatalkan_kegiatan(): void
    {
        $this->assertEquals('Dibatalkan', RiwayatMapper::statusMapper('Dibatalkan', 'kegiatan'));
    }

    // ── Null handling ──────────────────────────────────────────────────────

    public function test_null_maps_to_tidak_diketahui(): void
    {
        $this->assertEquals('Tidak Diketahui', RiwayatMapper::statusMapper(null));
    }

    // ── Status color mapping ───────────────────────────────────────────────

    public function test_status_color_menunggu(): void
    {
        $this->assertEquals('yellow', RiwayatMapper::statusColor('Menunggu'));
    }

    public function test_status_color_sukses(): void
    {
        $this->assertEquals('green', RiwayatMapper::statusColor('Sukses'));
    }

    public function test_status_color_gagal(): void
    {
        $this->assertEquals('red', RiwayatMapper::statusColor('Gagal'));
    }

    // ── Tipe label mapping ─────────────────────────────────────────────────

    public function test_tipe_label_donasi(): void
    {
        $this->assertEquals('Donasi', RiwayatMapper::tipeLabel('donasi'));
    }

    public function test_tipe_label_pembelian(): void
    {
        $this->assertEquals('Pembelian', RiwayatMapper::tipeLabel('pembelian'));
    }

    public function test_tipe_label_kegiatan(): void
    {
        $this->assertEquals('Kegiatan', RiwayatMapper::tipeLabel('kegiatan'));
    }

    // ── Numeric string handling ────────────────────────────────────────────

    public function test_numeric_string_1_maps_to_sukses(): void
    {
        $this->assertEquals('Sukses', RiwayatMapper::statusMapper('1'));
    }
}
