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
        // Drop the index that references the "status" column first.
        // Postgres/MySQL drop dependent indexes automatically, but SQLite
        // (used by the test database) does not, so we drop it explicitly.
        Schema::table('jenis_pohons', function (Blueprint $table) {
            $table->dropIndex('jenis_pohons_status_index');
        });

        Schema::table('jenis_pohons', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_pohons', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->index('status');
        });
    }
};
