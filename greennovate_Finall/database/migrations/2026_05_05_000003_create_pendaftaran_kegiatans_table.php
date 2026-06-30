<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('no_hp', 20);
            $table->string('alamat', 500);
            $table->enum('status', ['Terdaftar', 'Hadir', 'Selesai', 'Dibatalkan'])->default('Terdaftar');
            $table->string('qr_code')->nullable(); // path to QR code for ticket
            $table->string('bukti_dokumentasi')->nullable(); // photo/certificate
            $table->string('sertifikat')->nullable(); // certificate file path
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Prevent duplicate registrations
            $table->unique(['user_id', 'kegiatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_kegiatans');
    }
};
