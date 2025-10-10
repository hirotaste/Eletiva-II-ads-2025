<?php

namespace App\Repositories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository for Teacher model.
 * Handles all database operations related to teachers.
 */
class TeacherRepository
{
    /**
     * Get all active teachers.
     *
     * @return Collection<int, Teacher>
     */
    public function getAllActive(): Collection
    {
        return Teacher::where('is_active', true)->get();
    }

    /**
     * Find a teacher by ID.
     *
     * @param int $id
     * @return Teacher|null
     */
    public function findById(int $id): ?Teacher
    {
        return Teacher::find($id);
    }

    /**
     * Find an active teacher by ID or fail.
     *
     * @param int $id
     * @return Teacher
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Teacher
    {
        return Teacher::where('is_active', true)->findOrFail($id);
    }

    /**
     * Create a new teacher.
     *
     * @param array<string, mixed> $data
     * @return Teacher
     */
    public function create(array $data): Teacher
    {
        return Teacher::create($data);
    }

    /**
     * Update a teacher.
     *
     * @param Teacher $teacher
     * @param array<string, mixed> $data
     * @return Teacher
     */
    public function update(Teacher $teacher, array $data): Teacher
    {
        $teacher->update($data);
        return $teacher->fresh();
    }

    /**
     * Soft delete a teacher by setting is_active to false.
     *
     * @param Teacher $teacher
     * @return bool
     */
    public function deactivate(Teacher $teacher): bool
    {
        return $teacher->update(['is_active' => false]);
    }

    /**
     * Check if email exists for another teacher.
     *
     * @param string $email
     * @param int|null $excludeId
     * @return bool
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = Teacher::where('email', $email);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
