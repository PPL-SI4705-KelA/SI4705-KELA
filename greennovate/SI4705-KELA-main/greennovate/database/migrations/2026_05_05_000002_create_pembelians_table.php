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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_item');
            $table->integer('jumlah_item')->default(1);
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['Pending', 'Sukses', 'Gagal', 'Expired'])->default('Pending');
            $table->string('kode_transaksi')->unique();
            $table->string('qr_code')->nullable(); // path to QR image
            $table->string('bukti_dokumentasi')->nullable(); // path to documentation file
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
