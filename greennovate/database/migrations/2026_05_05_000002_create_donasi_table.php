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
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->string('metode_bayar', 50);
            $table->string('kode_transaksi', 100)->unique()->nullable();
            $table->enum('status', ['Pending', 'Sukses', 'Gagal', 'Kadaluarsa'])->default('Pending');
            $table->text('pesan')->nullable();
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
        Schema::dropIfExists('donasi');
    }
};
