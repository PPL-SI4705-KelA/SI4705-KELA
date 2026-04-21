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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->integer('target_pohon');
            $table->integer('kuota_tersisa');
            $table->string('status')->default('Belum Mulai');
            $table->timestamps();

            // Indexing untuk filter (GN-26 / PBI-54)
            $table->index('tanggal');
            $table->index('lokasi');
            $table->index('status');
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
