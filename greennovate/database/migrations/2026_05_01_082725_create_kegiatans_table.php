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
        Schema::dropIfExists('kegiatan');
        
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->nullable()->unique();
            $table->foreignId('lokasi_lahan_id')->constrained('lokasi_lahans')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('target_pohon');
            $table->integer('realisasi_pohon')->default(0);
            $table->integer('quota')->default(0);
            $table->integer('registered_count')->default(0);
            $table->enum('status', ['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan'])->default('Persiapan');
            $table->text('deskripsi')->nullable();
            $table->text('terms')->nullable();
            $table->string('image')->nullable();
            $table->datetime('registration_open_at')->nullable();
            $table->datetime('registration_close_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
