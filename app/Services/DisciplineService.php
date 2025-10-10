<?php

namespace App\Services;

use App\Models\Discipline;
use App\Repositories\DisciplineRepository;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service for Discipline business logic.
 * Handles complex operations and business rules for disciplines.
 */
class DisciplineService
{
    /**
     * Create a new DisciplineService instance.
     *
     * @param DisciplineRepository $repository
     * @return void
     */
    public function __construct(
        protected DisciplineRepository $repository
    ) {}

    /**
     * Get all active disciplines.
     *
     * @return Collection<int, Discipline>
     */
    public function getAllActive(): Collection
    {
        return $this->repository->getAllActive();
    }

    /**
     * Get a discipline by ID with related data.
     *
     * @param int $id
     * @return Discipline
     * @throws ResourceNotFoundException
     */
    public function getById(int $id): Discipline
    {
        $discipline = $this->repository->findById($id);
        
        if (!$discipline) {
            throw new ResourceNotFoundException('Discipline', $id);
        }
        
        return $this->repository->loadRelations($discipline);
    }

    /**
     * Create a new discipline.
     *
     * @param array<string, mixed> $data
     * @return Discipline
     * @throws ValidationException
     */
    public function create(array $data): Discipline
    {
        // Check for duplicate code
        if ($this->repository->codeExists($data['code'])) {
            throw new ValidationException('Code already exists', [
                'code' => ['The code has already been taken.']
            ]);
        }

        // Validate prerequisites if provided
        if (isset($data['prerequisites']) && !empty($data['prerequisites'])) {
            $this->validatePrerequisites($data['prerequisites']);
        }

        return $this->repository->create($data);
    }

    /**
     * Update a discipline.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Discipline
     * @throws ResourceNotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data): Discipline
    {
        $discipline = $this->repository->findById($id);
        
        if (!$discipline) {
            throw new ResourceNotFoundException('Discipline', $id);
        }

        // Check for duplicate code if code is being changed
        if (isset($data['code']) && $data['code'] !== $discipline->code) {
            if ($this->repository->codeExists($data['code'], $id)) {
                throw new ValidationException('Code already exists', [
                    'code' => ['The code has already been taken.']
                ]);
            }
        }

        // Validate prerequisites if provided
        if (isset($data['prerequisites']) && !empty($data['prerequisites'])) {
            $this->validatePrerequisites($data['prerequisites'], $id);
        }

        return $this->repository->update($discipline, $data);
    }

    /**
     * Deactivate a discipline.
     *
     * @param int $id
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function deactivate(int $id): bool
    {
        $discipline = $this->repository->findById($id);
        
        if (!$discipline) {
            throw new ResourceNotFoundException('Discipline', $id);
        }
        
        return $this->repository->deactivate($discipline);
    }

    /**
     * Validate prerequisites.
     * Ensures prerequisites exist and don't create circular dependencies.
     *
     * @param array<int> $prerequisiteIds
     * @param int|null $currentDisciplineId
     * @return void
     * @throws ValidationException
     */
    protected function validatePrerequisites(array $prerequisiteIds, ?int $currentDisciplineId = null): void
    {
        // Check if trying to add itself as prerequisite
        if ($currentDisciplineId && in_array($currentDisciplineId, $prerequisiteIds)) {
            throw new ValidationException('Invalid prerequisites', [
                'prerequisites' => ['A discipline cannot be its own prerequisite.']
            ]);
        }

        // Check if all prerequisites exist and are active
        foreach ($prerequisiteIds as $prereqId) {
            $prerequisite = $this->repository->findById($prereqId);
            
            if (!$prerequisite) {
                throw new ValidationException('Invalid prerequisites', [
                    'prerequisites' => ["Prerequisite with ID {$prereqId} does not exist."]
                ]);
            }
            
            if (!$prerequisite->is_active) {
                throw new ValidationException('Invalid prerequisites', [
                    'prerequisites' => ["Prerequisite '{$prerequisite->name}' is not active."]
                ]);
            }
        }
    }

    /**
     * Calculate total course workload.
     *
     * @param Collection<int, Discipline> $disciplines
     * @return int Total workload in hours
     */
    public function calculateTotalWorkload(Collection $disciplines): int
    {
        return $disciplines->sum('workload_hours');
    }
}
