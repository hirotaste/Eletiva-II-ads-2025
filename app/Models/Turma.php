<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Turma Model
 * 
 * Represents a class (turma) in the educational system.
 * 
 * @property int $id
 * @property int $periodo_letivo_id
 * @property int|null $disciplina_id
 * @property int|null $professor_id
 * @property string $codigo
 * @property int $vagas_total
 * @property int $vagas_ocupadas
 * @property \Illuminate\Support\Carbon $created_at
 */
class Turma extends Model
{
    use HasFactory;

    protected $table = 'turmas';

    public $timestamps = false;

    protected $fillable = [
        'periodo_letivo_id',
        'disciplina_id',
        'professor_id',
        'codigo',
        'vagas_total',
        'vagas_ocupadas'
    ];

    protected $casts = [
        'periodo_letivo_id' => 'integer',
        'disciplina_id' => 'integer',
        'professor_id' => 'integer',
        'vagas_total' => 'integer',
        'vagas_ocupadas' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the academic period for this class.
     *
     * @return BelongsTo
     */
    public function periodoLetivo(): BelongsTo
    {
        return $this->belongsTo(PeriodoLetivo::class, 'periodo_letivo_id');
    }

    /**
     * Get the discipline for this class.
     *
     * @return BelongsTo
     */
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    /**
     * Get the professor for this class.
     *
     * @return BelongsTo
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    /**
     * Get the enrollments for this class.
     *
     * @return HasMany
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'turma_id');
    }

    /**
     * Check if the class has available spots.
     *
     * @return bool
     */
    public function temVagasDisponiveis(): bool
    {
        return $this->vagas_ocupadas < $this->vagas_total;
    }

    /**
     * Get the number of available spots.
     *
     * @return int
     */
    public function getVagasDisponiveisAttribute(): int
    {
        return $this->vagas_total - $this->vagas_ocupadas;
    }
}
