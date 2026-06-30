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
        Schema::create('donasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_donasi');
            $table->decimal('jumlah', 15, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['Pending', 'Sukses', 'Gagal', 'Expired'])->default('Pending');
            $table->string('kode_transaksi')->unique();
            $table->string('bukti_dokumentasi')->nullable(); // path to file
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasis');
    }
};
