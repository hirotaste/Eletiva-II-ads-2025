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
        Schema::create('metricas_turma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->decimal('taxa_ocupacao', 5, 2)->nullable();
            $table->decimal('media_frequencia', 5, 2)->nullable();
            $table->decimal('taxa_aprovacao', 5, 2)->nullable();
            $table->integer('num_alunos_dependencia')->default(0);
            $table->timestamp('updated_at')->useCurrent();

            $table->unique(['turma_id', 'periodo_letivo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metricas_turma');
    }
};
