<?php

namespace App\Services;

use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service for Teacher business logic.
 * Handles complex operations and business rules for teachers.
 */
class TeacherService
{
    /**
     * Create a new TeacherService instance.
     *
     * @param TeacherRepository $repository
     * @return void
     */
    public function __construct(
        protected TeacherRepository $repository
    ) {}

    /**
     * Get all active teachers.
     *
     * @return Collection<int, Teacher>
     */
    public function getAllActive(): Collection
    {
        return $this->repository->getAllActive();
    }

    /**
     * Get a teacher by ID.
     *
     * @param int $id
     * @return Teacher
     * @throws ResourceNotFoundException
     */
    public function getById(int $id): Teacher
    {
        $teacher = $this->repository->findById($id);
        
        if (!$teacher) {
            throw new ResourceNotFoundException('Teacher', $id);
        }
        
        return $teacher;
    }

    /**
     * Create a new teacher.
     *
     * @param array<string, mixed> $data
     * @return Teacher
     * @throws ValidationException
     */
    public function create(array $data): Teacher
    {
        // Check for duplicate email
        if ($this->repository->emailExists($data['email'])) {
            throw new ValidationException('Email already exists', [
                'email' => ['The email has already been taken.']
            ]);
        }

        return $this->repository->create($data);
    }

    /**
     * Update a teacher.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Teacher
     * @throws ResourceNotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data): Teacher
    {
        $teacher = $this->getById($id);

        // Check for duplicate email if email is being changed
        if (isset($data['email']) && $data['email'] !== $teacher->email) {
            if ($this->repository->emailExists($data['email'], $id)) {
                throw new ValidationException('Email already exists', [
                    'email' => ['The email has already been taken.']
                ]);
            }
        }

        return $this->repository->update($teacher, $data);
    }

    /**
     * Deactivate a teacher.
     *
     * @param int $id
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function deactivate(int $id): bool
    {
        $teacher = $this->getById($id);
        return $this->repository->deactivate($teacher);
    }

    /**
     * Validate teacher availability.
     * Checks if the teacher's availability data is properly formatted.
     *
     * @param array<string, mixed> $availability
     * @return bool
     */
    public function validateAvailability(array $availability): bool
    {
        $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($availability as $day => $slots) {
            if (!in_array($day, $validDays)) {
                return false;
            }
            
            if (!is_array($slots)) {
                return false;
            }
        }
        
        return true;
    }
}
