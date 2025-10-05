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
            Schema::create('alunos', function (Blueprint $table) {
                $table->id();
                $table->string('matricula', 20)->unique();
                $table->string('nome', 150);
                $table->string('email', 150)->unique();
                $table->string('cpf', 14)->unique();
                $table->date('data_nascimento')->nullable();
                $table->string('telefone', 20)->nullable();
                $table->foreignId('curso_id')->nullable()->constrained('cursos')->onDelete('set null');
                $table->integer('semestre_atual');
                $table->integer('ano_ingresso');
                $table->integer('semestre_ingresso'); // 1 or 2
                $table->enum('status', ['ativo', 'trancado', 'formado', 'desistente'])->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });

            // Add check constraint via raw SQL
            DB::statement('ALTER TABLE alunos ADD CONSTRAINT check_semestre_ingresso CHECK (semestre_ingresso IN (1, 2))');
        } else {
            DB::statement('
                CREATE TABLE alunos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    matricula VARCHAR(20) UNIQUE NOT NULL,
                    nome VARCHAR(150) NOT NULL,
                    email VARCHAR(150) UNIQUE NOT NULL,
                    cpf VARCHAR(14) UNIQUE NOT NULL,
                    data_nascimento DATE,
                    telefone VARCHAR(20),
                    curso_id INTEGER,
                    semestre_atual INTEGER NOT NULL,
                    ano_ingresso INTEGER NOT NULL,
                    semestre_ingresso INTEGER NOT NULL CHECK (semestre_ingresso IN (1, 2)),
                    status VARCHAR(20),
                    ativo TINYINT(1) NOT NULL DEFAULT 1,
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY(curso_id) REFERENCES cursos(id) ON DELETE SET NULL
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
