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
        // Drop existing kegiatan table if exists (created manually or by another migration)
        Schema::dropIfExists('kegiatan');

        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location');
            $table->unsignedInteger('quota');
            $table->unsignedInteger('registered_count')->default(0);
            $table->text('terms')->nullable();
            $table->string('image')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('registration_open_at');
            $table->datetime('registration_close_at');
            $table->enum('status', ['open', 'closed', 'completed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
