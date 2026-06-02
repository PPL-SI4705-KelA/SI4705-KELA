<?php

namespace Tests\Browser;

use App\Models\Donasi;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminMonitoringTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clean up previous test donations
        Donasi::whereIn('kode_transaksi', ['DUSK-TX-001', 'DUSK-TX-002', 'DUSK-TX-003'])
            ->forceDelete();
    }

    private function prepareData()
    {
        // 1. Create or get admin
        $admin = User::firstOrCreate(
            ['email' => 'admin_dusk@greennovate.com'],
            [
                'name' => 'Admin Dusk',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'is_active' => 'true',
            ]
        );

        // 2. Create or get normal user
        $user = User::firstOrCreate(
            ['email' => 'user_dusk@greennovate.com'],
            [
                'name' => 'User Dusk',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user',
                'is_active' => 'true',
            ]
        );

        // 3. Create three donations
        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Dusk Pending',
            'jumlah' => 50000,
            'status' => 'Pending',
            'kode_transaksi' => 'DUSK-TX-001',
        ]);

        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Dusk Sukses',
            'jumlah' => 100000,
            'status' => 'Sukses',
            'kode_transaksi' => 'DUSK-TX-002',
        ]);

        Donasi::create([
            'user_id' => $user->id,
            'nama_donasi' => 'Donasi Dusk Expired',
            'jumlah' => 150000,
            'status' => 'Expired',
            'kode_transaksi' => 'DUSK-TX-003',
        ]);

        return $admin;
    }

    /**
     * E2E test: admin buka daftar donasi -> status tiap donasi tampil benar sesuai data.
     */
    public function testAdminCanSeeCorrectDonationStatuses(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = $this->prepareData();

            $browser->loginAs($admin)
                ->visit('/admin/donasi')
                ->assertSee('Daftar Transaksi Donasi')
                // Assert that the statuses are correctly mapped and rendered
                ->assertSee('Menunggu')     // Mapped from Pending
                ->assertSee('Berhasil')     // Mapped from Sukses
                ->assertSee('Kadaluarsa');  // Mapped from Expired
        });
    }
}
