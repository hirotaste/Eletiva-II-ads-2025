<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumMatrix extends Model
{
    use HasFactory;

    protected $table = 'curriculum_matrix';

    protected $fillable = [
        'code',
        'name',
        'year',
        'semester',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the disciplines in this curriculum matrix.
     */
    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'curriculum_disciplines')
            ->withPivot('semester', 'period')
            ->withTimestamps();
    }
}
