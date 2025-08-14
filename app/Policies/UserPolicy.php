<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->canManageUsers() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->canManageUsers() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can manage categories.
     */
    public function manageCategories(User $user): bool
    {
        return $user->canManageCategories();
    }

    /**
     * Determine whether the user can manage users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can view analytics.
     */
    public function viewAnalytics(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can export data.
     */
    public function exportData(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}