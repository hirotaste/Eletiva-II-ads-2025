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
        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nome', 100);
            $table->integer('capacidade');
            $table->enum('tipo', ['sala_aula', 'laboratorio', 'auditorio'])->nullable();
            $table->boolean('possui_projetor')->default(false);
            $table->boolean('possui_ar_condicionado')->default(false);
            $table->boolean('possui_computadores')->default(false);
            $table->boolean('ativo')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
