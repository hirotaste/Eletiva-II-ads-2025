<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Discipline Model
 * 
 * Represents a discipline/course in the educational system.
 * 
 * @property int $id Primary key
 * @property string $code Unique discipline code
 * @property string $name Discipline name
 * @property string|null $description Detailed description
 * @property int $workload_hours Total workload in hours
 * @property int $weekly_hours Weekly class hours
 * @property int $credits Number of credits
 * @property array|null $prerequisites JSON array of prerequisite discipline IDs
 * @property string $type Type of discipline (mandatory, elective, optional)
 * @property bool $is_active Whether the discipline is active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Discipline extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'workload_hours',
        'weekly_hours',
        'credits',
        'prerequisites',
        'type',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prerequisites' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the classes for this discipline.
     *
     * @return HasMany<ClassModel>
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class);
    }

    /**
     * Get the enrollments for this discipline.
     *
     * @return HasMany<Enrollment>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the curriculum matrices that include this discipline.
     *
     * @return BelongsToMany<CurriculumMatrix>
     */
    public function curriculumMatrices(): BelongsToMany
    {
        return $this->belongsToMany(CurriculumMatrix::class, 'curriculum_disciplines')
            ->withPivot('semester', 'period')
            ->withTimestamps();
    }
}

