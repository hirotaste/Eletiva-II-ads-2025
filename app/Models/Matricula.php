<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Matricula Model
 * 
 * Represents a student enrollment in a class.
 * 
 * @property int $id
 * @property int $aluno_id
 * @property int $turma_id
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $data_matricula
 * @property bool $is_dependencia
 */
class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    public $timestamps = false;

    protected $fillable = [
        'aluno_id',
        'turma_id',
        'status',
        'data_matricula',
        'is_dependencia'
    ];

    protected $casts = [
        'aluno_id' => 'integer',
        'turma_id' => 'integer',
        'data_matricula' => 'datetime',
        'is_dependencia' => 'boolean',
    ];

    /**
     * Get the student for this enrollment.
     *
     * @return BelongsTo
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    /**
     * Get the class for this enrollment.
     *
     * @return BelongsTo
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }
}
