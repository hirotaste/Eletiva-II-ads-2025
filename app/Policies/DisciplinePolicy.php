<?php

namespace App\Policies;

use App\Models\Discipline;
use App\Models\User;

/**
 * Policy for Discipline authorization.
 * Defines authorization rules for discipline-related operations.
 */
class DisciplinePolicy
{
    /**
     * Determine whether the user can view any disciplines.
     *
     * @param User|null $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        // Allow anyone to view disciplines list
        return true;
    }

    /**
     * Determine whether the user can view the discipline.
     *
     * @param User|null $user
     * @param Discipline $discipline
     * @return bool
     */
    public function view(?User $user, Discipline $discipline): bool
    {
        // Allow anyone to view a specific discipline
        return true;
    }

    /**
     * Determine whether the user can create disciplines.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Only authenticated users can create disciplines
        // In a real system, you would check for specific roles/permissions
        return true;
    }

    /**
     * Determine whether the user can update the discipline.
     *
     * @param User $user
     * @param Discipline $discipline
     * @return bool
     */
    public function update(User $user, Discipline $discipline): bool
    {
        // Only authenticated users can update disciplines
        // In a real system, you would check for specific roles/permissions
        return true;
    }

    /**
     * Determine whether the user can delete the discipline.
     *
     * @param User $user
     * @param Discipline $discipline
     * @return bool
     */
    public function delete(User $user, Discipline $discipline): bool
    {
        // Only authenticated users can delete disciplines
        // In a real system, you would check for admin role
        return true;
    }

    /**
     * Determine whether the user can restore the discipline.
     *
     * @param User $user
     * @param Discipline $discipline
     * @return bool
     */
    public function restore(User $user, Discipline $discipline): bool
    {
        // Only authenticated users can restore disciplines
        return true;
    }

    /**
     * Determine whether the user can permanently delete the discipline.
     *
     * @param User $user
     * @param Discipline $discipline
     * @return bool
     */
    public function forceDelete(User $user, Discipline $discipline): bool
    {
        // Only admins can permanently delete
        // In a real system, you would check for admin role
        return true;
    }
}
