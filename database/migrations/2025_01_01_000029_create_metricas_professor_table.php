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
        Schema::create('metricas_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professor_id')->constrained('professores')->onDelete('cascade');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->integer('num_turmas')->default(0);
            $table->integer('carga_horaria_total')->default(0);
            $table->decimal('percentual_preferencias_atendidas', 5, 2)->nullable();
            $table->integer('pontuacao_satisfacao')->default(0);
            $table->timestamp('updated_at')->useCurrent();

            $table->unique(['professor_id', 'periodo_letivo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metricas_professor');
    }
};
