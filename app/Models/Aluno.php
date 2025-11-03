<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Aluno Model
 * 
 * Represents a student in the educational system.
 * 
 * @property int $id
 * @property string $matricula
 * @property string $nome
 * @property string $email
 * @property string $cpf
 * @property string|null $data_nascimento
 * @property string|null $telefone
 * @property int|null $curso_id
 * @property int $semestre_atual
 * @property int $ano_ingresso
 * @property int $semestre_ingresso
 * @property string|null $status
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
        'matricula',
        'nome',
        'email',
        'cpf',
        'data_nascimento',
        'telefone',
        'curso_id',
        'semestre_atual',
        'ano_ingresso',
        'semestre_ingresso',
        'status',
        'ativo'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'curso_id' => 'integer',
        'semestre_atual' => 'integer',
        'ano_ingresso' => 'integer',
        'semestre_ingresso' => 'integer',
        'ativo' => 'boolean',
    ];

    /**
     * Get the course for this student.
     *
     * @return BelongsTo
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    /**
     * Get the enrollments for this student.
     *
     * @return HasMany
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'aluno_id');
    }
}
