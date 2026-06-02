<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gelar');
            $table->string('badge_icon');
            $table->decimal('threshold_o2', 10, 4); // kg O2/bulan
            $table->text('pesan_dampak');
            $table->timestamps();
        });

        // Seed data achievement sesuai spesifikasi
        DB::table('achievements')->insert([
            ['nama' => 'Penghirup Pertama',  'gelar' => 'Penghirup Pertama', 'badge_icon' => '🌱', 'threshold_o2' => 8.3,    'pesan_dampak' => 'Donasimu menghasilkan 8,3 kg O2/bulan — napas bumi dimulai darimu!',              'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Teman Oksigen',      'gelar' => 'Teman Oksigen',     'badge_icon' => '🌿', 'threshold_o2' => 41.5,   'pesan_dampak' => 'Kamu sudah berkontribusi pada 5 pohon penghasil O2!',                          'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Penjaga Napas',      'gelar' => 'Penjaga Napas',     'badge_icon' => '🌳', 'threshold_o2' => 83.0,   'pesan_dampak' => '10 pohon bernapas untukmu dan sesama setiap bulannya.',                      'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pelindung Hutan',    'gelar' => 'Pelindung Hutan',   'badge_icon' => '🏕️', 'threshold_o2' => 249.0,  'pesan_dampak' => '30 pohon! Kontribusimu setara menjaga satu sudut hutan kampus.',             'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pahlawan Hijau',     'gelar' => 'Pahlawan Hijau',    'badge_icon' => '🌲', 'threshold_o2' => 830.0,  'pesan_dampak' => '100 pohon tumbuh atas dukunganmu. Kampus lebih hijau karenamu!',             'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Legenda Oksigen',    'gelar' => 'Legenda Oksigen',   'badge_icon' => '🌍', 'threshold_o2' => 4150.0, 'pesan_dampak' => '500 pohon! Namamu terukir dalam sejarah penghijauan kampus.',               'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
