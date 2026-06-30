<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-01: Menampilkan landing page dengan info dasar dan CTA
     */
    #[Test]
    public function menampilkan_landing_page_dan_info_dasar()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Greennovate');
        $response->assertSee('Daftar Gratis');
    }

    /**
     * TC-02: Klik CTA mengarah ke halaman registrasi
     * Dalam feature test, kita mengecek apakah link CTA / tombol tersebut 
     * mengarah ke href="/register" atau route yang benar.
     */
    #[Test]
    public function klik_cta_mengarah_ke_register()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Memastikan terdapat link yang mengarah ke halaman register
        // Bisa dengan mengecek URL langsung atau rute nama.
        $response->assertSee('/register');
    }

    /**
     * TC-03: Aset gambar gagal load menampilkan placeholder
     * Dalam feature test (tanpa browser JS), kita hanya bisa memastikan
     * elemen gambar dan script/teks placeholder sudah dirender di HTML.
     */
    #[Test]
    public function aset_gambar_memiliki_handler_error_atau_placeholder()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Memastikan elemen gambar dan tag placeholder ada di HTML
        $response->assertSee('id="hero-img"', false);
        $response->assertSee('Foto Lahan Penghijauan');
    }
}
