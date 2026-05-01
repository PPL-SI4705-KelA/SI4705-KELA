<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel kegiatan penghijauan Greennovate.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();

            // Informasi utama
            $table->string('nama');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();

            // Target & kuota peserta
            $table->unsignedInteger('target')->default(0);   // target pohon/dsb
            $table->unsignedInteger('kuota')->default(0);    // maks pendaftar

            // Status kegiatan
            $table->enum('status', ['aktif', 'nonaktif', 'selesai'])->default('aktif');

            // Soft delete — agar kegiatan berpendaftar tidak bisa hard-delete
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
