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
            Schema::create('historico_academico', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
                $table->foreignId('disciplina_id')->nullable()->constrained('disciplinas')->onDelete('set null');
                $table->integer('ano');
                $table->integer('semestre'); // 1 or 2
                $table->decimal('nota', 4, 2)->nullable();
                $table->decimal('frequencia', 5, 2)->nullable();
                $table->enum('status', ['cursando', 'aprovado', 'reprovado', 'trancado'])->nullable();
                $table->boolean('is_dependencia')->default(false);
                $table->timestamp('created_at')->useCurrent();

                $table->unique(['aluno_id', 'disciplina_id', 'ano', 'semestre']);
            });

            // Add check constraint via raw SQL
            DB::statement('ALTER TABLE historico_academico ADD CONSTRAINT check_hist_semestre CHECK (semestre IN (1, 2))');
        } else {
            DB::statement('
                CREATE TABLE historico_academico (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    aluno_id INTEGER NOT NULL,
                    disciplina_id INTEGER,
                    ano INTEGER NOT NULL,
                    semestre INTEGER NOT NULL CHECK (semestre IN (1, 2)),
                    nota DECIMAL(4, 2),
                    frequencia DECIMAL(5, 2),
                    status VARCHAR(20),
                    is_dependencia TINYINT(1) NOT NULL DEFAULT 0,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY(aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
                    FOREIGN KEY(disciplina_id) REFERENCES disciplinas(id) ON DELETE SET NULL,
                    UNIQUE(aluno_id, disciplina_id, ano, semestre)
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_academico');
    }
};
