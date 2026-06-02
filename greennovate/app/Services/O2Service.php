<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Donasi;
use App\Models\Notifikasi;
use App\Models\UserAchievement;
use App\Models\UserO2Stat;
use Illuminate\Support\Facades\DB;

class O2Service
{
    const O2_PER_POHON = 8.3; // kg O2/bulan per pohon

    /**
     * Dipanggil setiap kali donasi berhasil (status = Sukses).
     * Recalculate total O2 user dari semua donasi sukses, lalu cek achievement.
     */
    public function recalculateForUser(int $userId): void
    {
        DB::transaction(function () use ($userId) {
            $totalPohon = $this->hitungTotalPohon($userId);
            $totalO2    = round($totalPohon * self::O2_PER_POHON, 4);

            // Upsert user_o2_stats
            UserO2Stat::updateOrCreate(
                ['user_id' => $userId],
                [
                    'total_pohon'           => $totalPohon,
                    'total_o2_kg_per_bulan' => $totalO2,
                    'last_updated'          => now(),
                ]
            );

            // Cek dan berikan achievement baru
            $this->checkAchievements($userId, $totalO2);
        });
    }

    /**
     * Hitung total pohon dari semua donasi sukses pada kegiatan pohon.
     */
    public function hitungTotalPohon(int $userId): float
    {
        $donasis = Donasi::where('user_id', $userId)
            ->where('status', 'Sukses')
            ->whereNotNull('kegiatan_id')
            ->with(['kegiatan' => function ($q) {
                $q->whereIn('tipe_kegiatan', ['tanam_pohon', 'beli_pohon'])
                  ->where('target_dana', '>', 0)
                  ->whereNotNull('estimasi_pohon');
            }])
            ->get();

        $totalPohon = 0;
        foreach ($donasis as $donasi) {
            if (!$donasi->kegiatan) continue;

            $kegiatan = $donasi->kegiatan;
            $proporsi = $donasi->jumlah / $kegiatan->target_dana;
            $pohon    = $proporsi * $kegiatan->estimasi_pohon;
            $totalPohon += $pohon;
        }

        return round($totalPohon, 4);
    }

    /**
     * Cek threshold achievement dan beri badge baru jika memenuhi syarat.
     */
    private function checkAchievements(int $userId, float $totalO2): void
    {
        $achievements = Achievement::where('threshold_o2', '<=', $totalO2)
            ->orderBy('threshold_o2')
            ->get();

        // Achievement yang sudah dimiliki
        $alreadyHas = UserAchievement::where('user_id', $userId)
            ->pluck('achievement_id')
            ->toArray();

        foreach ($achievements as $achievement) {
            if (in_array($achievement->id, $alreadyHas)) {
                continue; // Tidak duplikat
            }

            // Simpan achievement baru
            UserAchievement::create([
                'user_id'        => $userId,
                'achievement_id' => $achievement->id,
                'o2_saat_unlock' => $totalO2,
                'diraih_pada'    => now(),
                'is_notified'    => false,
            ]);

            // Buat notifikasi in-app
            Notifikasi::create([
                'user_id' => $userId,
                'judul'   => "Badge Baru: {$achievement->badge_icon} {$achievement->nama}",
                'pesan'   => "Selamat! Kamu baru saja mendapat badge {$achievement->nama}. {$achievement->pesan_dampak}",
                'tipe'    => 'achievement',
                'is_read' => false,
            ]);
        }
    }

    /**
     * Ambil badge berikutnya yang belum diraih.
     */
    public function getBadgeBerikutnya(int $userId): ?Achievement
    {
        $totalO2 = UserO2Stat::where('user_id', $userId)->value('total_o2_kg_per_bulan') ?? 0;

        $alreadyHas = UserAchievement::where('user_id', $userId)
            ->pluck('achievement_id')
            ->toArray();

        return Achievement::where('threshold_o2', '>', $totalO2)
            ->whereNotIn('id', $alreadyHas)
            ->orderBy('threshold_o2')
            ->first();
    }

    /**
     * Hitung pesan O2 untuk satu donasi.
     */
    public function pesanDonasi(Donasi $donasi): ?string
    {
        if (!$donasi->kegiatan) return null;
        $kegiatan = $donasi->kegiatan;

        if (!in_array($kegiatan->tipe_kegiatan, ['tanam_pohon', 'beli_pohon'])) return null;
        if ($kegiatan->target_dana <= 0 || !$kegiatan->estimasi_pohon) return null;

        $proporsi = $donasi->jumlah / $kegiatan->target_dana;
        $pohon    = round($proporsi * $kegiatan->estimasi_pohon, 4);
        $o2       = round($pohon * self::O2_PER_POHON, 2);

        return "Donasimu menghasilkan {$o2} kg O2 setiap bulannya — setara {$pohon} pohon!";
    }
}
