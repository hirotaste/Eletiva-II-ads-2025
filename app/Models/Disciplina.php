<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Disciplina Model
 * 
 * Represents a discipline/subject in the educational system.
 * 
 * @property int $id
 * @property string $codigo
 * @property string $nome
 * @property int $carga_horaria
 * @property int $creditos
 * @property string|null $ementa
 * @property string|null $tipo
 * @property int|null $semestre_ideal
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Disciplina extends Model
{
    use HasFactory;

    protected $table = 'disciplinas';

    protected $fillable = [
        'codigo',
        'nome',
        'carga_horaria',
        'creditos',
        'ementa',
        'tipo',
        'semestre_ideal',
        'ativo'
    ];

    protected $casts = [
        'carga_horaria' => 'integer',
        'creditos' => 'integer',
        'semestre_ideal' => 'integer',
        'ativo' => 'boolean',
    ];

    /**
     * Get the classes for this discipline.
     *
     * @return HasMany
     */
    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class, 'disciplina_id');
    }
}
