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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('building');
            $table->string('floor');
            $table->integer('capacity');
            $table->enum('type', ['lecture', 'lab', 'auditorium', 'seminar']);
            $table->json('resources')->nullable(); // projector, computers, whiteboard, etc.
            $table->boolean('has_accessibility')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
