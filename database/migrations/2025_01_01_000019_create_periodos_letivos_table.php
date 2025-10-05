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
            Schema::create('periodos_letivos', function (Blueprint $table) {
                $table->id();
                $table->integer('ano');
                $table->integer('semestre'); // 1 or 2
                $table->date('data_inicio');
                $table->date('data_fim');
                $table->enum('status', ['planejamento', 'ativo', 'finalizado'])->nullable();

                $table->unique(['ano', 'semestre']);
            });

            // Add check constraint via raw SQL
            DB::statement('ALTER TABLE periodos_letivos ADD CONSTRAINT check_periodo_semestre CHECK (semestre IN (1, 2))');
        } else {
            DB::statement('
                CREATE TABLE periodos_letivos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    ano INTEGER NOT NULL,
                    semestre INTEGER NOT NULL CHECK (semestre IN (1, 2)),
                    data_inicio DATE NOT NULL,
                    data_fim DATE NOT NULL,
                    status VARCHAR(20),
                    UNIQUE(ano, semestre)
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos_letivos');
    }
};
