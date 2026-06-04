<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Notifikasi;
use Carbon\Carbon;

class AchievementService
{
    /**
     * Mengecek dan memberikan badge achievement jika user mencapai threshold O2 tertentu.
     * 
     * @param int|string $userId
     * @param float $totalO2
     * @return void
     */
    public function checkAchievements($userId, $totalO2)
    {
        // Ambil semua achievement yang thresholdnya sudah tercapai, diurutkan dari yang terendah ke tertinggi
        $eligibleAchievements = Achievement::where('threshold_o2', '<=', $totalO2)
            ->orderBy('threshold_o2', 'asc')
            ->get();

        foreach ($eligibleAchievements as $achievement) {
            // Cek apakah user sudah punya achievement ini
            $hasAchievement = UserAchievement::where('user_id', $userId)
                ->where('achievement_id', $achievement->id)
                ->exists();

            if (!$hasAchievement) {
                // Insert badge baru
                UserAchievement::create([
                    'user_id' => $userId,
                    'achievement_id' => $achievement->id,
                    'o2_saat_unlock' => $totalO2,
                    'diraih_pada' => Carbon::now(),
                    'is_notified' => \Illuminate\Support\Facades\DB::raw('false'),
                ]);

                // Buat notifikasi (PB-24 format)
                $pesanNotif = "Selamat! Kamu baru saja mendapat badge {$achievement->nama_gelar}. Donasimu kini menghasilkan " . number_format($totalO2, 1, ',', '.') . " kg O2/bulan! " . $achievement->pesan_dampak;
                
                Notifikasi::create([
                    'user_id' => $userId,
                    'judul' => 'Badge Baru: ' . $achievement->nama_gelar . ' ' . $achievement->icon_badge,
                    'pesan' => $pesanNotif,
                    'tipe' => 'sistem',
                    'is_read' => \Illuminate\Support\Facades\DB::raw('false'),
                ]);
            }
        }
    }
}
