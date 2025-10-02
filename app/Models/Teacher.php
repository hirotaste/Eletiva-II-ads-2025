<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'employment_type',
        'availability',
        'preferences',
        'is_active'
    ];

    protected $casts = [
        'availability' => 'array',
        'preferences' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the classes taught by this teacher.
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
}
