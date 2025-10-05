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
        Schema::create('alertas_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50);
            $table->enum('severidade', ['info', 'warning', 'error', 'critical'])->nullable();
            $table->string('titulo', 200);
            $table->text('mensagem');
            $table->string('entidade_tipo', 50)->nullable();
            $table->integer('entidade_id')->nullable();
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->onDelete('set null');
            $table->boolean('lido')->default(false);
            $table->boolean('resolvido')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_sistema');
    }
};
