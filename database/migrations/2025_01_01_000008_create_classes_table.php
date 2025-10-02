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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('year');
            $table->integer('semester');
            $table->enum('shift', ['morning', 'afternoon', 'evening', 'night']);
            $table->integer('max_students');
            $table->integer('enrolled_students')->default(0);
            $table->json('schedule')->nullable(); // Days of week and time slots
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
