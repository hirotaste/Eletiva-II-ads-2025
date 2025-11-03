<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Sala Model
 * 
 * Represents a classroom/room in the educational system.
 * 
 * @property int $id
 * @property string $codigo
 * @property string $nome
 * @property int $capacidade
 * @property string|null $tipo
 * @property bool $possui_projetor
 * @property bool $possui_ar_condicionado
 * @property bool $possui_computadores
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon $created_at
 */
class Sala extends Model
{
    use HasFactory;

    protected $table = 'salas';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nome',
        'capacidade',
        'tipo',
        'possui_projetor',
        'possui_ar_condicionado',
        'possui_computadores',
        'ativo'
    ];

    protected $casts = [
        'capacidade' => 'integer',
        'possui_projetor' => 'boolean',
        'possui_ar_condicionado' => 'boolean',
        'possui_computadores' => 'boolean',
        'ativo' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get available resources as a comma-separated string.
     *
     * @return string
     */
    public function getRecursosDisponiveisAttribute(): string
    {
        $recursos = [];
        if ($this->possui_projetor) $recursos[] = 'Projetor';
        if ($this->possui_ar_condicionado) $recursos[] = 'Ar Condicionado';
        if ($this->possui_computadores) $recursos[] = 'Computadores';
        
        return implode(', ', $recursos) ?: 'Nenhum';
    }
}
