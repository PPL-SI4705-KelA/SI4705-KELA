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
        Schema::create('pendaftaran_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->string('nama_lengkap', 255);
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->enum('status', ['Menunggu', 'Dikonfirmasi', 'Ditolak', 'Selesai'])->default('Menunggu');
            $table->string('qr_code')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->text('catatan')->nullable();
            $table->unique(['user_id', 'kegiatan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_kegiatan');
    }
};
