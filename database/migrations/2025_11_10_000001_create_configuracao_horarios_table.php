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
        Schema::create('configuracao_horarios', function (Blueprint $table) {
            $table->id();
            $table->integer('dia_semana'); // 1-7 (Domingo a Sábado)
            $table->boolean('ativo')->default(true);
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->string('descricao')->nullable();
            $table->timestamps();

            $table->index(['dia_semana', 'ativo']);
        });

        // Inserir configuração padrão (Segunda a Sexta, 7h às 22h)
        DB::table('configuracao_horarios')->insert([
            ['dia_semana' => 2, 'ativo' => true, 'hora_inicio' => '07:00:00', 'hora_fim' => '12:00:00', 'descricao' => 'Manhã', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 2, 'ativo' => true, 'hora_inicio' => '13:00:00', 'hora_fim' => '18:00:00', 'descricao' => 'Tarde', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 2, 'ativo' => true, 'hora_inicio' => '19:00:00', 'hora_fim' => '22:00:00', 'descricao' => 'Noite', 'created_at' => now(), 'updated_at' => now()],
            
            ['dia_semana' => 3, 'ativo' => true, 'hora_inicio' => '07:00:00', 'hora_fim' => '12:00:00', 'descricao' => 'Manhã', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 3, 'ativo' => true, 'hora_inicio' => '13:00:00', 'hora_fim' => '18:00:00', 'descricao' => 'Tarde', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 3, 'ativo' => true, 'hora_inicio' => '19:00:00', 'hora_fim' => '22:00:00', 'descricao' => 'Noite', 'created_at' => now(), 'updated_at' => now()],
            
            ['dia_semana' => 4, 'ativo' => true, 'hora_inicio' => '07:00:00', 'hora_fim' => '12:00:00', 'descricao' => 'Manhã', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 4, 'ativo' => true, 'hora_inicio' => '13:00:00', 'hora_fim' => '18:00:00', 'descricao' => 'Tarde', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 4, 'ativo' => true, 'hora_inicio' => '19:00:00', 'hora_fim' => '22:00:00', 'descricao' => 'Noite', 'created_at' => now(), 'updated_at' => now()],
            
            ['dia_semana' => 5, 'ativo' => true, 'hora_inicio' => '07:00:00', 'hora_fim' => '12:00:00', 'descricao' => 'Manhã', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 5, 'ativo' => true, 'hora_inicio' => '13:00:00', 'hora_fim' => '18:00:00', 'descricao' => 'Tarde', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 5, 'ativo' => true, 'hora_inicio' => '19:00:00', 'hora_fim' => '22:00:00', 'descricao' => 'Noite', 'created_at' => now(), 'updated_at' => now()],
            
            ['dia_semana' => 6, 'ativo' => true, 'hora_inicio' => '07:00:00', 'hora_fim' => '12:00:00', 'descricao' => 'Manhã', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 6, 'ativo' => true, 'hora_inicio' => '13:00:00', 'hora_fim' => '18:00:00', 'descricao' => 'Tarde', 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 6, 'ativo' => true, 'hora_inicio' => '19:00:00', 'hora_fim' => '22:00:00', 'descricao' => 'Noite', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracao_horarios');
    }
};
