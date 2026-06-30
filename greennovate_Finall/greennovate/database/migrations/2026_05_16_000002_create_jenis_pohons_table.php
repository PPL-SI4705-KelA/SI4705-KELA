<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * PB-14: Tabel jenis pohon & harga.
     * Menggunakan soft delete (deleted_at) agar data historis tetap aman.
     */
    public function up(): void
    {
        Schema::create('jenis_pohons', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->string('nama_latin', 100)->nullable();
            $table->foreignId('kategori_pohon_id')
                  ->constrained('kategori_pohons')
                  ->onDelete('restrict');
            $table->decimal('harga', 12, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('version')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('nama');
            $table->index('kategori_pohon_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pohons');
    }
};
