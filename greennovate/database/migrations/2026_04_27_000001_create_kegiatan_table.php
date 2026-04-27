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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->integer('target_pohon');
            $table->integer('kuota_total');
            $table->integer('kuota_terisi')->default(0);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'penuh', 'selesai'])->default('aktif');
            $table->timestamps();

            // PBI-54: Index untuk kolom yang sering difilter (tanggal, lokasi, status)
            $table->index('tanggal');
            $table->index('lokasi');
            $table->index('status');
            // Composite index untuk query filter gabungan
            $table->index(['status', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
