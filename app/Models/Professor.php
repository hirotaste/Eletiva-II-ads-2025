<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Professor Model
 * 
 * Represents a teacher/professor in the educational system.
 * 
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $cpf
 * @property string|null $telefone
 * @property string|null $titulacao
 * @property int $carga_horaria_maxima
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Professor extends Model
{
    use HasFactory;

    protected $table = 'professores';

    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'telefone',
        'titulacao',
        'carga_horaria_maxima',
        'ativo'
    ];

    protected $casts = [
        'carga_horaria_maxima' => 'integer',
        'ativo' => 'boolean',
    ];

    /**
     * Get the classes taught by this professor.
     *
     * @return HasMany
     */
    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class, 'professor_id');
    }
}
