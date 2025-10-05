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
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50);
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->string('titulo', 200);
            $table->text('descricao')->nullable();
            $table->json('dados')->nullable();
            $table->enum('formato', ['json', 'pdf', 'csv'])->nullable();
            $table->string('caminho_arquivo', 300)->nullable();
            $table->integer('gerado_por')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios');
    }
};
