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
        Schema::create('otimizacao_solucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execucao_id')->constrained('otimizacao_execucoes')->onDelete('cascade');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->integer('versao');
            $table->decimal('fitness_score', 10, 4);
            $table->integer('num_conflitos')->default(0);
            $table->integer('num_janelas_total')->default(0);
            $table->decimal('media_janelas_aluno', 6, 2)->nullable();
            $table->boolean('aprovada')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['periodo_letivo_id', 'versao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otimizacao_solucoes');
    }
};
