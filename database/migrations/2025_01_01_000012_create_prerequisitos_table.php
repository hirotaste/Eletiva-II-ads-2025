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
        // For PostgreSQL and MySQL, we can add the check constraint
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::create('prerequisitos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
                $table->foreignId('prerequisito_id')->constrained('disciplinas')->onDelete('cascade');
                $table->enum('tipo', ['obrigatorio', 'recomendado'])->nullable();
                $table->timestamps();

                $table->unique(['disciplina_id', 'prerequisito_id']);
            });

            // Add check constraint via raw SQL
            DB::statement('ALTER TABLE prerequisitos ADD CONSTRAINT check_disciplina_prerequisito CHECK (disciplina_id != prerequisito_id)');
        } else {
            // For SQLite, we need to include CHECK in CREATE TABLE
            DB::statement('
                CREATE TABLE prerequisitos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    disciplina_id INTEGER NOT NULL,
                    prerequisito_id INTEGER NOT NULL,
                    tipo VARCHAR(20),
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY(disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
                    FOREIGN KEY(prerequisito_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
                    UNIQUE(disciplina_id, prerequisito_id),
                    CHECK (disciplina_id != prerequisito_id)
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prerequisitos');
    }
};
