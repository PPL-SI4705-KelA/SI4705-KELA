<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah semua data lama ke format baru dulu (biar tidak crash constraint)
        DB::table('donasis')
            ->where('status', 'Pending')
            ->update(['status' => 'pending']);

        DB::table('donasis')
            ->where('status', 'Menunggu Pembayaran')
            ->update(['status' => 'pending']);

        DB::table('donasis')
            ->where('status', 'Menunggu Verifikasi Admin')
            ->update(['status' => 'menunggu_verifikasi']);

        DB::table('donasis')
            ->where('status', 'Sukses')
            ->update(['status' => 'sukses']);

        DB::table('donasis')
            ->where('status', 'Gagal')
            ->update(['status' => 'gagal']);

        DB::table('donasis')
            ->where('status', 'Expired')
            ->update(['status' => 'expired']);

        // 2. Drop constraint lama (NEON / POSTGRES WAJIB BEGINI)
        DB::statement('ALTER TABLE donasis DROP CONSTRAINT IF EXISTS donasis_status_check');

        // 3. Buat constraint baru
        DB::statement("
            ALTER TABLE donasis
            ADD CONSTRAINT donasis_status_check
            CHECK (status IN (
                'pending',
                'menunggu_verifikasi',
                'sukses',
                'gagal',
                'expired'
            ))
        ");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE donasis DROP CONSTRAINT IF EXISTS donasis_status_check');

        DB::statement("
            ALTER TABLE donasis
            ADD CONSTRAINT donasis_status_check
            CHECK (status IN (
                'Pending',
                'Menunggu Verifikasi Admin',
                'Sukses',
                'Gagal',
                'Expired'
            ))
        ");
    }
};