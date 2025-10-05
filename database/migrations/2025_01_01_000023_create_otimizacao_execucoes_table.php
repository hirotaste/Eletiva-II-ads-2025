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
        Schema::create('otimizacao_execucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->string('algoritmo', 50);
            $table->json('parametros')->nullable();
            $table->decimal('fitness_inicial', 10, 4)->nullable();
            $table->decimal('fitness_final', 10, 4)->nullable();
            $table->integer('geracoes_executadas')->nullable();
            $table->integer('tempo_execucao_segundos')->nullable();
            $table->enum('status', ['executando', 'concluido', 'erro'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otimizacao_execucoes');
    }
};
