<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para gerenciar preferências de disciplinas dos professores
 * 
 * Representa a associação entre professores e disciplinas que podem lecionar,
 * incluindo o nível de preferência (1-5)
 * 
 * @package App\Models
 */
class ProfessorDisciplina extends Model
{
    use HasFactory;

    /**
     * Nome da tabela no banco de dados
     *
     * @var string
     */
    protected $table = 'professor_disciplinas';

    /**
     * Atributos que podem ser atribuídos em massa
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'professor_id',
        'disciplina_id',
        'preferencia',
    ];

    /**
     * Cast de atributos para tipos nativos
     *
     * @var array<string, string>
     */
    protected $casts = [
        'professor_id' => 'integer',
        'disciplina_id' => 'integer',
        'preferencia' => 'integer',
    ];

    /**
     * Relacionamento: Preferência pertence a um Professor
     *
     * @return BelongsTo
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    /**
     * Relacionamento: Preferência pertence a uma Disciplina
     *
     * @return BelongsTo
     */
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    /**
     * Scope para filtrar por professor
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $professorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProfessor($query, int $professorId)
    {
        return $query->where('professor_id', $professorId);
    }

    /**
     * Scope para filtrar por disciplina
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $disciplinaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDisciplina($query, int $disciplinaId)
    {
        return $query->where('disciplina_id', $disciplinaId);
    }

    /**
     * Scope para ordenar por preferência (maior primeiro)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByPreferencia($query)
    {
        return $query->orderBy('preferencia', 'desc');
    }

    /**
     * Obtém a descrição textual do nível de preferência
     *
     * @return string
     */
    public function getPreferenciaTextoAttribute(): string
    {
        return match($this->preferencia) {
            1 => 'Muito Baixa',
            2 => 'Baixa',
            3 => 'Média',
            4 => 'Alta',
            5 => 'Muito Alta',
            default => 'Não definida'
        };
    }

    /**
     * Obtém a cor do badge baseado na preferência
     *
     * @return string
     */
    public function getPreferenciaCorAttribute(): string
    {
        return match($this->preferencia) {
            1 => 'danger',
            2 => 'warning',
            3 => 'info',
            4 => 'primary',
            5 => 'success',
            default => 'secondary'
        };
    }

    /**
     * Validações customizadas do modelo
     *
     * @param int $preferencia
     * @return bool
     */
    public static function isPreferenciaValida(int $preferencia): bool
    {
        return $preferencia >= 1 && $preferencia <= 5;
    }
}
