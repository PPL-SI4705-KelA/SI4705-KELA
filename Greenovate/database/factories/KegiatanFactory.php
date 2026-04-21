<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kegiatan>
 */
class KegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kegiatan' => 'Penanaman Pohon di ' . $this->faker->city(),
            'lokasi' => $this->faker->city(),
            'tanggal' => $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'target_pohon' => $this->faker->numberBetween(100, 1000),
            'kuota_tersisa' => $this->faker->numberBetween(0, 50),
            'status' => $this->faker->randomElement(['Belum Mulai', 'Sedang Berjalan', 'Selesai']),
        ];
    }
}
