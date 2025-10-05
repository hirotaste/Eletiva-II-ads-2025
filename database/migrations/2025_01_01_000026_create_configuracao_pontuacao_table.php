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
        Schema::create('configuracao_pontuacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->integer('peso_janela_1h')->default(-5);
            $table->integer('peso_janela_2h')->default(-15);
            $table->integer('peso_janela_3h_mais')->default(-25);
            $table->integer('bonus_dia_sem_janela')->default(10);
            $table->integer('bonus_aulas_sequenciais')->default(5);
            $table->integer('peso_conflito_horario')->default(-50);
            $table->integer('peso_preferencia_professor')->default(3);
            $table->boolean('ativo')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracao_pontuacao');
    }
};
