<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PB-21: Tabel realisasi pencatatan pohon yang ditanam oleh petugas.
     * Menyimpan detail per-pencatatan: jenis pohon, jumlah, catatan, dan waktu.
     */
    public function up(): void
    {
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_pohon_id')->constrained('jenis_pohons')->onDelete('restrict');
            $table->integer('jumlah')->unsigned();
            $table->text('catatan')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->index('kegiatan_id');
            $table->index('petugas_id');
            $table->index('recorded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasis');
    }
};
