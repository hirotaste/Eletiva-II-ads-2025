<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Teacher Model
 * 
 * Represents a teacher in the educational system.
 * 
 * @property int $id Primary key
 * @property string $name Teacher's full name
 * @property string $email Teacher's email address (unique)
 * @property string|null $phone Teacher's phone number
 * @property string $specialization Teacher's area of expertise
 * @property string $employment_type Employment type (full_time, part_time, contract)
 * @property array|null $availability JSON array of available time slots
 * @property array|null $preferences JSON array of teaching preferences
 * @property bool $is_active Whether the teacher is active in the system
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'availability' => 'array',
        'preferences' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the classes taught by this teacher.
     *
     * @return HasMany<ClassModel>
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class);
    }
}

