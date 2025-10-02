<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'name',
        'email',
        'phone',
        'birth_date',
        'enrollment_date',
        'status',
        'history',
        'gpa'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'history' => 'array',
        'gpa' => 'decimal:2',
    ];

    /**
     * Get the enrollments for this student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the disciplines this student is enrolled in.
     */
    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'enrollments')
            ->withPivot('year', 'semester', 'status', 'grade', 'attendance_percentage')
            ->withTimestamps();
    }
}
