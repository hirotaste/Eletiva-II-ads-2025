<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para gerenciar a disponibilidade de horários dos professores
 * 
 * Representa os horários em que um professor está disponível para lecionar
 * Segue o princípio Single Responsibility (SOLID)
 */
class ProfessorDisponibilidade extends Model
{
    use HasFactory;

    /**
     * Nome da tabela no banco de dados
     */
    protected $table = 'professor_disponibilidade';

    /**
     * Atributos que podem ser atribuídos em massa
     */
    protected $fillable = [
        'professor_id',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
        'preferencia',
    ];

    /**
     * Tipos de casting para os atributos
     */
    protected $casts = [
        'dia_semana' => 'integer',
        'preferencia' => 'integer',
    ];

    /**
     * Constantes para os dias da semana
     */
    public const DOMINGO = 1;
    public const SEGUNDA = 2;
    public const TERCA = 3;
    public const QUARTA = 4;
    public const QUINTA = 5;
    public const SEXTA = 6;
    public const SABADO = 7;

    /**
     * Mapa de dias da semana
     */
    public static function diasSemana(): array
    {
        return [
            self::DOMINGO => 'Domingo',
            self::SEGUNDA => 'Segunda-feira',
            self::TERCA => 'Terça-feira',
            self::QUARTA => 'Quarta-feira',
            self::QUINTA => 'Quinta-feira',
            self::SEXTA => 'Sexta-feira',
            self::SABADO => 'Sábado',
        ];
    }

    /**
     * Retorna o nome do dia da semana
     */
    public function getNomeDiaAttribute(): string
    {
        return self::diasSemana()[$this->dia_semana] ?? 'Desconhecido';
    }

    /**
     * Relacionamento: Disponibilidade pertence a um Professor
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    /**
     * Scope para filtrar por professor
     */
    public function scopePorProfessor($query, int $professorId)
    {
        return $query->where('professor_id', $professorId);
    }

    /**
     * Scope para filtrar por dia da semana
     */
    public function scopePorDia($query, int $diaSemana)
    {
        return $query->where('dia_semana', $diaSemana);
    }

    /**
     * Scope para ordenar por dia e hora
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('dia_semana')
                     ->orderBy('hora_inicio');
    }

    /**
     * Verifica se há conflito com outro horário
     */
    public function temConflitoCom(string $horaInicio, string $horaFim): bool
    {
        return !($horaFim <= $this->hora_inicio || $horaInicio >= $this->hora_fim);
    }

    /**
     * Retorna todos os horários do professor em formato de grade
     */
    public static function getGradeProfessor(int $professorId): array
    {
        $disponibilidades = self::where('professor_id', $professorId)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $grade = [];
        
        // Inicializa grade vazia (24 horas x 7 dias)
        for ($hora = 0; $hora < 24; $hora++) {
            for ($dia = 1; $dia <= 7; $dia++) {
                $grade[$hora][$dia] = [
                    'disponivel' => false,
                    'preferencia' => null,
                ];
            }
        }

        // Preenche com as disponibilidades
        foreach ($disponibilidades as $disp) {
            $horaInicio = (int) substr($disp->hora_inicio, 0, 2);
            $horaFim = (int) substr($disp->hora_fim, 0, 2);
            
            for ($h = $horaInicio; $h < $horaFim; $h++) {
                $grade[$h][$disp->dia_semana] = [
                    'disponivel' => true,
                    'preferencia' => $disp->preferencia,
                ];
            }
        }

        return $grade;
    }
}
