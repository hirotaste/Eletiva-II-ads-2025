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
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::create('professor_disciplinas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professor_id')->constrained('professores')->onDelete('cascade');
                $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
                $table->integer('preferencia')->nullable(); // 1-5 scale
                $table->timestamps();

                $table->unique(['professor_id', 'disciplina_id']);
            });

            // Add check constraint via raw SQL
            DB::statement('ALTER TABLE professor_disciplinas ADD CONSTRAINT check_prof_disc_preferencia CHECK (preferencia BETWEEN 1 AND 5)');
        } else {
            DB::statement('
                CREATE TABLE professor_disciplinas (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    professor_id INTEGER NOT NULL,
                    disciplina_id INTEGER NOT NULL,
                    preferencia INTEGER CHECK (preferencia BETWEEN 1 AND 5),
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY(professor_id) REFERENCES professores(id) ON DELETE CASCADE,
                    FOREIGN KEY(disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
                    UNIQUE(professor_id, disciplina_id)
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor_disciplinas');
    }
};
