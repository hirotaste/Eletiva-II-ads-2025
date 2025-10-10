<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;

/**
 * Policy for Teacher authorization.
 * Defines authorization rules for teacher-related operations.
 */
class TeacherPolicy
{
    /**
     * Determine whether the user can view any teachers.
     *
     * @param User|null $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        // Allow anyone to view teachers list
        return true;
    }

    /**
     * Determine whether the user can view the teacher.
     *
     * @param User|null $user
     * @param Teacher $teacher
     * @return bool
     */
    public function view(?User $user, Teacher $teacher): bool
    {
        // Allow anyone to view a specific teacher
        return true;
    }

    /**
     * Determine whether the user can create teachers.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Only authenticated users can create teachers
        // In a real system, you would check for specific roles/permissions
        return true;
    }

    /**
     * Determine whether the user can update the teacher.
     *
     * @param User $user
     * @param Teacher $teacher
     * @return bool
     */
    public function update(User $user, Teacher $teacher): bool
    {
        // Only authenticated users can update teachers
        // In a real system, you would check for specific roles/permissions
        return true;
    }

    /**
     * Determine whether the user can delete the teacher.
     *
     * @param User $user
     * @param Teacher $teacher
     * @return bool
     */
    public function delete(User $user, Teacher $teacher): bool
    {
        // Only authenticated users can delete teachers
        // In a real system, you would check for admin role
        return true;
    }

    /**
     * Determine whether the user can restore the teacher.
     *
     * @param User $user
     * @param Teacher $teacher
     * @return bool
     */
    public function restore(User $user, Teacher $teacher): bool
    {
        // Only authenticated users can restore teachers
        return true;
    }

    /**
     * Determine whether the user can permanently delete the teacher.
     *
     * @param User $user
     * @param Teacher $teacher
     * @return bool
     */
    public function forceDelete(User $user, Teacher $teacher): bool
    {
        // Only admins can permanently delete
        // In a real system, you would check for admin role
        return true;
    }
}
