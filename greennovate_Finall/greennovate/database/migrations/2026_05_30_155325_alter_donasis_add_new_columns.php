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
        Schema::table('donasis', function (Blueprint $table) {

            // Relasi ke kegiatan penghijauan
            $table->foreignId('kegiatan_id')
                ->nullable()
                ->after('user_id')
                ->constrained('kegiatan')
                ->nullOnDelete();

            // Data donatur sesuai Jira
            $table->string('nama_donatur')
                ->nullable()
                ->after('kegiatan_id');

            $table->string('nomor_hp', 20)
                ->nullable()
                ->after('nama_donatur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {

            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['kegiatan_id']);

            $table->dropColumn([
                'kegiatan_id',
                'nama_donatur',
                'nomor_hp',
            ]);
        });
    }
};