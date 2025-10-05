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
        Schema::create('metricas_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->foreignId('solucao_id')->nullable()->constrained('otimizacao_solucoes')->onDelete('set null');
            $table->integer('num_disciplinas')->default(0);
            $table->integer('carga_horaria_semanal')->default(0);
            $table->integer('num_janelas_total')->default(0);
            $table->integer('num_janelas_1h')->default(0);
            $table->integer('num_janelas_2h')->default(0);
            $table->integer('num_janelas_3h_mais')->default(0);
            $table->integer('dias_com_aula')->default(0);
            $table->integer('dias_sem_janela')->default(0);
            $table->integer('pontuacao_janelas')->default(0);
            $table->integer('pontuacao_total')->default(0);
            $table->decimal('percentual_aproveitamento', 5, 2)->nullable();
            $table->timestamp('updated_at')->useCurrent();

            $table->unique(['aluno_id', 'periodo_letivo_id', 'solucao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metricas_aluno');
    }
};
