<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'discipline_id',
        'year',
        'semester',
        'status',
        'grade',
        'attendance_percentage'
    ];

    protected $casts = [
        'grade' => 'decimal:2',
    ];

    /**
     * Get the student for this enrollment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the discipline for this enrollment.
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
