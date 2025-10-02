<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

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

    protected $casts = [
        'prerequisites' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the classes for this discipline.
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    /**
     * Get the enrollments for this discipline.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the curriculum matrices that include this discipline.
     */
    public function curriculumMatrices()
    {
        return $this->belongsToMany(CurriculumMatrix::class, 'curriculum_disciplines')
            ->withPivot('semester', 'period')
            ->withTimestamps();
    }
}
