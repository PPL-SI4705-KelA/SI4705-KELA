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
            $table->string('slug')->unique()->nullable();
            $table->foreignId('lokasi_lahan_id')->constrained('lokasi_lahans')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade'); // The officer assigned
            $table->date('tanggal');
            $table->integer('target_pohon');
            $table->integer('realisasi_pohon')->default(0);
            $table->enum('status', ['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan'])->default('Persiapan');
            $table->text('deskripsi')->nullable();
            $table->integer('quota')->default(0);
            $table->integer('registered_count')->default(0);
            $table->text('terms')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('registration_open_at')->nullable();
            $table->timestamp('registration_close_at')->nullable();
            $table->timestamps();
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
