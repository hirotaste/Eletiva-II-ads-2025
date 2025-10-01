<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'building',
        'floor',
        'capacity',
        'type',
        'resources',
        'has_accessibility',
        'is_active'
    ];

    protected $casts = [
        'resources' => 'array',
        'has_accessibility' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the classes held in this classroom.
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
}
