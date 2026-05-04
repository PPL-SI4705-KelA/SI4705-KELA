<?php

namespace Tests\Unit;

use App\Http\Controllers\RiwayatController;
use PHPUnit\Framework\TestCase;

class RiwayatStatusMapperTest extends TestCase
{
    protected RiwayatController $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new RiwayatController();
    }

    // ── Kegiatan Status Tests ───────────────────────────────────────────────

    public function test_kegiatan_menunggu_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['kegiatan', 'Menunggu']);
        $this->assertEquals('Menunggu', $result['label']);
        $this->assertEquals('yellow', $result['color']);
    }

    public function test_kegiatan_dikonfirmasi_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['kegiatan', 'Dikonfirmasi']);
        $this->assertEquals('Dikonfirmasi', $result['label']);
        $this->assertEquals('blue', $result['color']);
    }

    public function test_kegiatan_selesai_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['kegiatan', 'Selesai']);
        $this->assertEquals('Selesai', $result['label']);
        $this->assertEquals('green', $result['color']);
    }

    public function test_kegiatan_ditolak_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['kegiatan', 'Ditolak']);
        $this->assertEquals('Ditolak', $result['label']);
        $this->assertEquals('red', $result['color']);
    }

    public function test_kegiatan_unknown_defaults_to_menunggu(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['kegiatan', 'UNKNOWN']);
        $this->assertEquals('Menunggu', $result['label']);
        $this->assertEquals('yellow', $result['color']);
    }

    // ── Donasi Status Tests ─────────────────────────────────────────────────

    public function test_donasi_sukses_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['donasi', 'Sukses']);
        $this->assertEquals('Sukses', $result['label']);
        $this->assertEquals('green', $result['color']);
    }

    public function test_donasi_pending_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['donasi', 'Pending']);
        $this->assertEquals('Pending', $result['label']);
        $this->assertEquals('yellow', $result['color']);
    }

    public function test_donasi_gagal_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['donasi', 'Gagal']);
        $this->assertEquals('Gagal', $result['label']);
        $this->assertEquals('red', $result['color']);
    }

    public function test_donasi_kadaluarsa_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['donasi', 'Kadaluarsa']);
        $this->assertEquals('Kadaluarsa', $result['label']);
        $this->assertEquals('gray', $result['color']);
    }

    // ── Pembelian Status Tests ──────────────────────────────────────────────

    public function test_pembelian_dikirim_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['pembelian', 'Dikirim']);
        $this->assertEquals('Dikirim', $result['label']);
        $this->assertEquals('blue', $result['color']);
    }

    public function test_pembelian_dibatalkan_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['pembelian', 'Dibatalkan']);
        $this->assertEquals('Dibatalkan', $result['label']);
        $this->assertEquals('red', $result['color']);
    }

    public function test_pembelian_selesai_maps_correctly(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['pembelian', 'Selesai']);
        $this->assertEquals('Selesai', $result['label']);
        $this->assertEquals('gray', $result['color']);
    }

    // ── Edge Cases ──────────────────────────────────────────────────────────

    public function test_unknown_model_returns_raw_status_with_gray(): void
    {
        $result = $this->invokePrivateMethod('statusMapper', ['unknown_model', 'SomeStatus']);
        $this->assertEquals('SomeStatus', $result['label']);
        $this->assertEquals('gray', $result['color']);
    }

    public function test_unknown_status_returns_default_color(): void
    {
        // For donasi/pembelian, unknown status defaults to Pending (yellow)
        $result = $this->invokePrivateMethod('statusMapper', ['donasi', 'UNKNOWN_STATUS']);
        $this->assertEquals('Pending', $result['label']);
        $this->assertEquals('yellow', $result['color']);
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    private function invokePrivateMethod(string $method, array $args): mixed
    {
        $ref = new \ReflectionMethod(RiwayatController::class, $method);
        $ref->setAccessible(true);
        return $ref->invokeArgs($this->controller, $args);
    }
}
