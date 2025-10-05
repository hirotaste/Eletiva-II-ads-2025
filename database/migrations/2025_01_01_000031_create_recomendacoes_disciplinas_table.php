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
        Schema::create('recomendacoes_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->foreignId('disciplina_id')->nullable()->constrained('disciplinas')->onDelete('set null');
            $table->foreignId('turma_id')->nullable()->constrained('turmas')->onDelete('set null');
            $table->decimal('score_compatibilidade', 5, 2)->nullable();
            $table->integer('reduz_janelas')->nullable();
            $table->text('motivo_recomendacao')->nullable();
            $table->boolean('prerequisitos_ok')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recomendacoes_disciplinas');
    }
};
