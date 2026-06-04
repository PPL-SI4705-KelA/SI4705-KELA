<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'nama_gelar' => 'Penghirup Pertama',
                'threshold_o2' => 8.3,
                'pesan_dampak' => 'Donasimu menghasilkan 8,3 kg O2/bulan — napas bumi dimulai darimu!',
                'icon_badge' => '🌱',
            ],
            [
                'nama_gelar' => 'Teman Oksigen',
                'threshold_o2' => 41.5,
                'pesan_dampak' => 'Kamu sudah berkontribusi pada 5 pohon penghasil O2!',
                'icon_badge' => '🌿',
            ],
            [
                'nama_gelar' => 'Penjaga Napas',
                'threshold_o2' => 83.0,
                'pesan_dampak' => '10 pohon bernapas untukmu dan sesama setiap bulannya.',
                'icon_badge' => '🌳',
            ],
            [
                'nama_gelar' => 'Pelindung Hutan',
                'threshold_o2' => 249.0,
                'pesan_dampak' => '30 pohon! Kontribusimu setara menjaga satu sudut hutan kampus.',
                'icon_badge' => '🏕️',
            ],
            [
                'nama_gelar' => 'Pahlawan Hijau',
                'threshold_o2' => 830.0,
                'pesan_dampak' => '100 pohon tumbuh atas dukungamu. Kampus lebih hijau karenamu!',
                'icon_badge' => '🌲',
            ],
            [
                'nama_gelar' => 'Legenda Oksigen',
                'threshold_o2' => 4150.0,
                'pesan_dampak' => '500 pohon! Namamu terukir dalam sejarah penghijauan kampus.',
                'icon_badge' => '🌍',
            ],
        ];

        foreach ($badges as $badge) {
            DB::table('achievements')->updateOrInsert(
                ['nama_gelar' => $badge['nama_gelar']],
                array_merge($badge, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
