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
            Schema::create('turma_horarios', function (Blueprint $table) {
                $table->id();
                $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
                $table->foreignId('sala_id')->nullable()->constrained('salas')->onDelete('set null');
                $table->integer('dia_semana'); // 1=Monday, 7=Sunday
                $table->time('hora_inicio');
                $table->time('hora_fim');
                $table->timestamp('created_at')->useCurrent();
            });

            // Add check constraints via raw SQL
            DB::statement('ALTER TABLE turma_horarios ADD CONSTRAINT check_turma_dia_semana CHECK (dia_semana BETWEEN 1 AND 7)');
            DB::statement('ALTER TABLE turma_horarios ADD CONSTRAINT check_turma_horas CHECK (hora_fim > hora_inicio)');
        } else {
            DB::statement('
                CREATE TABLE turma_horarios (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    turma_id INTEGER NOT NULL,
                    sala_id INTEGER,
                    dia_semana INTEGER NOT NULL CHECK (dia_semana BETWEEN 1 AND 7),
                    hora_inicio TIME NOT NULL,
                    hora_fim TIME NOT NULL CHECK (hora_fim > hora_inicio),
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY(turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
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
        Schema::dropIfExists('turma_horarios');
    }
};
