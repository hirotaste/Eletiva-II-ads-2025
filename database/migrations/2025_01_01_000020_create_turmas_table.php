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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_letivo_id')->constrained('periodos_letivos')->onDelete('cascade');
            $table->foreignId('disciplina_id')->nullable()->constrained('disciplinas')->onDelete('set null');
            $table->foreignId('professor_id')->nullable()->constrained('professores')->onDelete('set null');
            $table->string('codigo', 20);
            $table->integer('vagas_total');
            $table->integer('vagas_ocupadas')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['periodo_letivo_id', 'codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
