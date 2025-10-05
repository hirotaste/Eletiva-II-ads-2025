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
        Schema::create('simulacoes_matricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->string('nome_simulacao', 100)->nullable();
            $table->json('turmas_selecionadas')->nullable();
            $table->integer('num_janelas_resultante')->nullable();
            $table->integer('pontuacao_resultante')->nullable();
            $table->json('conflitos')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulacoes_matricula');
    }
};
