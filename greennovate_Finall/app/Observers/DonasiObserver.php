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
     * @return void
     */
    public function created(Donasi $donasi)
    {
        if (strtolower($donasi->status) === 'sukses') {
            $this->processGamification($donasi);
        }
    }

    /**
     * Handle the Donasi "updated" event.
     *
     * @param  \App\Models\Donasi  $donasi
     * @return void
     */
    public function updated(Donasi $donasi)
    {
        if ($donasi->wasChanged('status') && strtolower($donasi->status) === 'sukses') {
            $this->processGamification($donasi);
        }
    }

    private function processGamification(Donasi $donasi)
    {
        $kegiatan = $donasi->kegiatan;

        if ($kegiatan && in_array($kegiatan->tipe_kegiatan, ['tanam_pohon', 'beli_pohon'])) {
            
            $targetDana = floatval($kegiatan->target_dana);
            $estimasiPohon = intval($kegiatan->estimasi_pohon);
            
            if ($targetDana > 0 && $estimasiPohon > 0) {
                $proporsiPohon = (floatval($donasi->jumlah) / $targetDana) * $estimasiPohon;
                $tambahanO2 = $proporsiPohon * 8.3;

                $stat = UserO2Stat::firstOrCreate(
                    ['user_id' => $donasi->user_id],
                    ['total_pohon' => 0, 'total_o2_kg_per_bulan' => 0]
                );

                $stat->total_pohon += $proporsiPohon;
                $stat->total_o2_kg_per_bulan += $tambahanO2;
                $stat->save();

                $achievementService = app(AchievementService::class);
                $achievementService->checkAchievements($donasi->user_id, $stat->total_o2_kg_per_bulan);
            }
        }
    }
}
