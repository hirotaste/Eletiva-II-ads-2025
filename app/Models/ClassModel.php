<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'code',
        'discipline_id',
        'teacher_id',
        'classroom_id',
        'year',
        'semester',
        'shift',
        'max_students',
        'enrolled_students',
        'schedule',
        'is_active'
    ];

    protected $casts = [
        'schedule' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the discipline for this class.
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Get the teacher for this class.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the classroom for this class.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
