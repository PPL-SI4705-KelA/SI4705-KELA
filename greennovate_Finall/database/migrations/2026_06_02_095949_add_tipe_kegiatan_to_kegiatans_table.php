<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->enum('tipe_kegiatan', ['tanam_pohon', 'beli_pohon', 'lainnya'])->default('lainnya')->after('nama');
            $table->integer('estimasi_pohon')->nullable()->after('tipe_kegiatan');
            $table->decimal('target_dana', 15, 2)->default(0)->after('target_pohon');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn(['tipe_kegiatan', 'estimasi_pohon', 'target_dana']);
        });
    }
};
