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
            Schema::create('professor_disponibilidade', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professor_id')->constrained('professores')->onDelete('cascade');
                $table->integer('dia_semana'); // 1=Monday, 7=Sunday
                $table->time('hora_inicio');
                $table->time('hora_fim');
                $table->integer('preferencia')->nullable(); // 1-5 scale
                $table->timestamps();

                $table->unique(['professor_id', 'dia_semana', 'hora_inicio']);
            });

            // Add check constraints via raw SQL
            DB::statement('ALTER TABLE professor_disponibilidade ADD CONSTRAINT check_dia_semana CHECK (dia_semana BETWEEN 1 AND 7)');
            DB::statement('ALTER TABLE professor_disponibilidade ADD CONSTRAINT check_preferencia CHECK (preferencia BETWEEN 1 AND 5)');
        } else {
            DB::statement('
                CREATE TABLE professor_disponibilidade (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    professor_id INTEGER NOT NULL,
                    dia_semana INTEGER NOT NULL CHECK (dia_semana BETWEEN 1 AND 7),
                    hora_inicio TIME NOT NULL,
                    hora_fim TIME NOT NULL,
                    preferencia INTEGER CHECK (preferencia BETWEEN 1 AND 5),
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY(professor_id) REFERENCES professores(id) ON DELETE CASCADE,
                    UNIQUE(professor_id, dia_semana, hora_inicio)
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor_disponibilidade');
    }
};
