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
            Schema::create('solucao_alocacoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('solucao_id')->constrained('otimizacao_solucoes')->onDelete('cascade');
                $table->foreignId('turma_id')->nullable()->constrained('turmas')->onDelete('set null');
                $table->foreignId('sala_id')->nullable()->constrained('salas')->onDelete('set null');
                $table->integer('dia_semana'); // 1=Monday, 7=Sunday
                $table->time('hora_inicio');
                $table->time('hora_fim');
            });

            // Add check constraint via raw SQL
            DB::statement('ALTER TABLE solucao_alocacoes ADD CONSTRAINT check_solucao_dia_semana CHECK (dia_semana BETWEEN 1 AND 7)');
        } else {
            DB::statement('
                CREATE TABLE solucao_alocacoes (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    solucao_id INTEGER NOT NULL,
                    turma_id INTEGER,
                    sala_id INTEGER,
                    dia_semana INTEGER NOT NULL CHECK (dia_semana BETWEEN 1 AND 7),
                    hora_inicio TIME NOT NULL,
                    hora_fim TIME NOT NULL,
                    FOREIGN KEY(solucao_id) REFERENCES otimizacao_solucoes(id) ON DELETE CASCADE,
                    FOREIGN KEY(turma_id) REFERENCES turmas(id) ON DELETE SET NULL,
                    FOREIGN KEY(sala_id) REFERENCES salas(id) ON DELETE SET NULL
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solucao_alocacoes');
    }
};
