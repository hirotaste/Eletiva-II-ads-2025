<?php

namespace App\Repositories;

use App\Models\Discipline;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository for Discipline model.
 * Handles all database operations related to disciplines.
 */
class DisciplineRepository
{
    /**
     * Get all active disciplines.
     *
     * @return Collection<int, Discipline>
     */
    public function getAllActive(): Collection
    {
        return Discipline::where('is_active', true)->get();
    }

    /**
     * Find a discipline by ID.
     *
     * @param int $id
     * @return Discipline|null
     */
    public function findById(int $id): ?Discipline
    {
        return Discipline::find($id);
    }

    /**
     * Find an active discipline by ID or fail.
     *
     * @param int $id
     * @return Discipline
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Discipline
    {
        return Discipline::where('is_active', true)->findOrFail($id);
    }

    /**
     * Create a new discipline.
     *
     * @param array<string, mixed> $data
     * @return Discipline
     */
    public function create(array $data): Discipline
    {
        return Discipline::create($data);
    }

    /**
     * Update a discipline.
     *
     * @param Discipline $discipline
     * @param array<string, mixed> $data
     * @return Discipline
     */
    public function update(Discipline $discipline, array $data): Discipline
    {
        $discipline->update($data);
        return $discipline->fresh();
    }

    /**
     * Soft delete a discipline by setting is_active to false.
     *
     * @param Discipline $discipline
     * @return bool
     */
    public function deactivate(Discipline $discipline): bool
    {
        return $discipline->update(['is_active' => false]);
    }

    /**
     * Check if code exists for another discipline.
     *
     * @param string $code
     * @param int|null $excludeId
     * @return bool
     */
    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $query = Discipline::where('code', $code);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Get disciplines with their related data.
     *
     * @param Discipline $discipline
     * @return Discipline
     */
    public function loadRelations(Discipline $discipline): Discipline
    {
        return $discipline->load(['classes', 'curriculumMatrices']);
    }
}
