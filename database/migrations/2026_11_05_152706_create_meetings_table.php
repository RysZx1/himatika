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
        Schema::create('meetings', function     (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('meeting_date');
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('minutes_file')->nullable(); // File PDF/Doc Notulensi Rapat
            $table->enum('target_role', ['all', 'pengurus'])->default('pengurus');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
