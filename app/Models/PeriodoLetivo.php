<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PeriodoLetivo Model
 * 
 * Represents an academic period (semester) in the educational system.
 * 
 * @property int $id
 * @property int $ano
 * @property int $semestre
 * @property string $data_inicio
 * @property string $data_fim
 * @property string|null $status
 */
class PeriodoLetivo extends Model
{
    use HasFactory;

    protected $table = 'periodos_letivos';

    public $timestamps = false;

    protected $fillable = [
        'ano',
        'semestre',
        'data_inicio',
        'data_fim',
        'status'
    ];

    protected $casts = [
        'ano' => 'integer',
        'semestre' => 'integer',
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * Get the classes for this academic period.
     *
     * @return HasMany
     */
    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class, 'periodo_letivo_id');
    }

    /**
     * Get a formatted display name for the period.
     *
     * @return string
     */
    public function getNomeCompletoAttribute(): string
    {
        return "{$this->ano}/{$this->semestre}";
    }
}
