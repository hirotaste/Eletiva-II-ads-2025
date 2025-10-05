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
        Schema::create('metricas_sistema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->foreignId('solucao_id')->nullable()->constrained('otimizacao_solucoes')->onDelete('set null');
            $table->integer('total_turmas')->default(0);
            $table->integer('total_alunos')->default(0);
            $table->integer('total_professores')->default(0);
            $table->decimal('taxa_aproveitamento_salas', 5, 2)->nullable();
            $table->decimal('media_janelas_geral', 6, 2)->nullable();
            $table->integer('num_conflitos_total')->default(0);
            $table->decimal('percentual_alunos_sem_janela', 5, 2)->nullable();
            $table->decimal('percentual_otimizacao', 5, 2)->nullable();
            $table->timestamp('updated_at')->useCurrent();

            $table->unique(['periodo_letivo_id', 'solucao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metricas_sistema');
    }
};
