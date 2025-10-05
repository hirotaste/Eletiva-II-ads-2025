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
        Schema::table('alunos', function (Blueprint $table) {
            $table->index('curso_id', 'idx_alunos_curso');
            $table->index('status', 'idx_alunos_status');
        });

        Schema::table('historico_academico', function (Blueprint $table) {
            $table->index('aluno_id', 'idx_historico_aluno');
            $table->index('disciplina_id', 'idx_historico_disciplina');
        });

        Schema::table('turmas', function (Blueprint $table) {
            $table->index('periodo_letivo_id', 'idx_turmas_periodo');
            $table->index('disciplina_id', 'idx_turmas_disciplina');
        });

        Schema::table('turma_horarios', function (Blueprint $table) {
            $table->index('dia_semana', 'idx_turma_horarios_dia');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            $table->index('aluno_id', 'idx_matriculas_aluno');
            $table->index('turma_id', 'idx_matriculas_turma');
        });

        Schema::table('metricas_aluno', function (Blueprint $table) {
            $table->index(['aluno_id', 'periodo_letivo_id'], 'idx_metricas_aluno_periodo');
        });

        Schema::table('professor_disponibilidade', function (Blueprint $table) {
            $table->index(['professor_id', 'dia_semana'], 'idx_professor_disponibilidade');
        });

        Schema::table('recomendacoes_disciplinas', function (Blueprint $table) {
            $table->index(['aluno_id', 'periodo_letivo_id'], 'idx_recomendacoes_aluno');
        });

        Schema::table('alertas_sistema', function (Blueprint $table) {
            $table->index(['lido', 'severidade'], 'idx_alertas_nao_lidos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropIndex('idx_alunos_curso');
            $table->dropIndex('idx_alunos_status');
        });

        Schema::table('historico_academico', function (Blueprint $table) {
            $table->dropIndex('idx_historico_aluno');
            $table->dropIndex('idx_historico_disciplina');
        });

        Schema::table('turmas', function (Blueprint $table) {
            $table->dropIndex('idx_turmas_periodo');
            $table->dropIndex('idx_turmas_disciplina');
        });

        Schema::table('turma_horarios', function (Blueprint $table) {
            $table->dropIndex('idx_turma_horarios_dia');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropIndex('idx_matriculas_aluno');
            $table->dropIndex('idx_matriculas_turma');
        });

        Schema::table('metricas_aluno', function (Blueprint $table) {
            $table->dropIndex('idx_metricas_aluno_periodo');
        });

        Schema::table('professor_disponibilidade', function (Blueprint $table) {
            $table->dropIndex('idx_professor_disponibilidade');
        });

        Schema::table('recomendacoes_disciplinas', function (Blueprint $table) {
            $table->dropIndex('idx_recomendacoes_aluno');
        });

        Schema::table('alertas_sistema', function (Blueprint $table) {
            $table->dropIndex('idx_alertas_nao_lidos');
        });
    }
};
