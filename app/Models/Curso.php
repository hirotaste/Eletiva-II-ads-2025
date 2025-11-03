<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Curso Model
 * 
 * Represents a course in the educational system.
 * 
 * @property int $id
 * @property string $nome
 * @property string $codigo
 * @property int $duracao_semestres
 * @property int $carga_horaria_total
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'nome',
        'codigo',
        'duracao_semestres',
        'carga_horaria_total',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'duracao_semestres' => 'integer',
        'carga_horaria_total' => 'integer',
    ];

    /**
     * Get the students enrolled in this course.
     *
     * @return HasMany
     */
    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class, 'curso_id');
    }
}
