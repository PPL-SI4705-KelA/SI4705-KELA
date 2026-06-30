<?php

namespace App\Observers;

use App\Models\Donasi;
use App\Models\UserO2Stat;
use App\Services\AchievementService;

class DonasiObserver
{
    /**
     * Handle the Donasi "updated" event.
     *
     * @param  \App\Models\Donasi  $donasi
     * @return void
     */
    public function updated(Donasi $donasi)
    {
        // Cek jika status berubah menjadi 'Sukses' (untuk database pgsql enum mungkin 'Sukses' atau sesuai konfigurasi)
        // Kita bandingkan jika wasChanged dan nilai barunya adalah 'Sukses'
        if ($donasi->wasChanged('status') && strtolower($donasi->status) === 'sukses') {
            
            $kegiatan = $donasi->kegiatan;

            if ($kegiatan && in_array($kegiatan->tipe_kegiatan, ['tanam_pohon', 'beli_pohon'])) {
                
                // Pastikan target dana valid (tidak nol) untuk menghindari pembagian dengan nol
                $targetDana = floatval($kegiatan->target_dana);
                $estimasiPohon = intval($kegiatan->estimasi_pohon);
                
                if ($targetDana > 0 && $estimasiPohon > 0) {
                    // Kalkulasi proporsi pohon
                    $proporsiPohon = (floatval($donasi->jumlah) / $targetDana) * $estimasiPohon;
                    
                    // Kalkulasi O2: 1 pohon = 8.3 kg O2 / bulan
                    $tambahanO2 = $proporsiPohon * 8.3;

                    // Update atau Create UserO2Stat
                    $stat = UserO2Stat::firstOrCreate(
                        ['user_id' => $donasi->user_id],
                        ['total_pohon' => 0, 'total_o2_kg_per_bulan' => 0]
                    );

                    $stat->total_pohon += $proporsiPohon;
                    $stat->total_o2_kg_per_bulan += $tambahanO2;
                    $stat->save();

                    // Jalankan achievement checker
                    $achievementService = app(AchievementService::class);
                    $achievementService->checkAchievements($donasi->user_id, $stat->total_o2_kg_per_bulan);
                }
            }
        }
    }
}
