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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_produk', 255);
            $table->string('kategori', 100)->nullable();
            $table->integer('jumlah_item')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_harga', 12, 2);
            $table->string('metode_bayar', 50);
            $table->string('kode_transaksi', 100)->unique()->nullable();
            $table->enum('status', ['Pending', 'Sukses', 'Dikirim', 'Selesai', 'Dibatalkan'])->default('Pending');
            $table->string('qr_code')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
