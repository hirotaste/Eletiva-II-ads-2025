<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model para configuração geral de horários da instituição
 * 
 * Define os dias e horários de funcionamento da instituição
 * Segue o princípio Single Responsibility (SOLID)
 */
class ConfiguracaoHorario extends Model
{
    use HasFactory;

    /**
     * Nome da tabela no banco de dados
     */
    protected $table = 'configuracao_horarios';

    /**
     * Atributos que podem ser atribuídos em massa
     */
    protected $fillable = [
        'dia_semana',
        'ativo',
        'hora_inicio',
        'hora_fim',
        'descricao',
    ];

    /**
     * Tipos de casting para os atributos
     */
    protected $casts = [
        'dia_semana' => 'integer',
        'ativo' => 'boolean',
    ];

    /**
     * Retorna configurações ativas por dia
     */
    public static function getHorariosPorDia(int $diaSemana): array
    {
        return self::where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->orderBy('hora_inicio')
            ->get()
            ->toArray();
    }

    /**
     * Verifica se a instituição funciona no dia/hora especificado
     */
    public static function isFuncionando(int $diaSemana, string $hora): bool
    {
        return self::where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->where('hora_inicio', '<=', $hora)
            ->where('hora_fim', '>', $hora)
            ->exists();
    }

    /**
     * Retorna todos os horários configurados em formato de grade
     */
    public static function getGradeCompleta(): array
    {
        $configuracoes = self::where('ativo', true)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $grade = [];
        
        // Inicializa grade vazia
        for ($dia = 1; $dia <= 7; $dia++) {
            $grade[$dia] = [
                'ativo' => false,
                'horarios' => [],
            ];
        }

        // Preenche com as configurações
        foreach ($configuracoes as $config) {
            $grade[$config->dia_semana]['ativo'] = true;
            $grade[$config->dia_semana]['horarios'][] = [
                'id' => $config->id,
                'inicio' => $config->hora_inicio,
                'fim' => $config->hora_fim,
                'descricao' => $config->descricao,
            ];
        }

        return $grade;
    }
}
