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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('semester');
            $table->enum('status', ['enrolled', 'completed', 'failed', 'withdrawn']);
            $table->decimal('grade', 4, 2)->nullable();
            $table->integer('attendance_percentage')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'discipline_id', 'year', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
