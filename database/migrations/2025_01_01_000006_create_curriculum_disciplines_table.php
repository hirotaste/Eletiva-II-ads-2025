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
        Schema::create('curriculum_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_matrix_id')->constrained('curriculum_matrix')->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->integer('semester'); // Which semester this discipline belongs to
            $table->integer('period'); // Morning, Afternoon, Evening (1, 2, 3)
            $table->timestamps();

            $table->unique(['curriculum_matrix_id', 'discipline_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_disciplines');
    }
};
