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
            $table->string('status')->default('Pending')->change();
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To avoid data loss or enum constraint errors, we keep it as string
        // but just to be complete, you could change it back here if needed.
        // We will leave it as string to prevent issues.
    }
};
