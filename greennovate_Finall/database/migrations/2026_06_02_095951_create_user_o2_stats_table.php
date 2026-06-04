<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_o2_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->decimal('total_pohon', 10, 2)->default(0);
            $table->decimal('total_o2_kg_per_bulan', 10, 2)->default(0);
            $table->timestamps(); // includes created_at and updated_at (Last Updated)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_o2_stats');
    }
};
