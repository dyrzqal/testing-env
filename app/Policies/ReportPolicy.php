<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canManageReports();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        if ($user->isAdmin() || $user->isModerator()) {
            return true;
        }
        
        if ($user->isInvestigator()) {
            return $report->assigned_to_user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canManageReports();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        if ($user->isAdmin() || $user->isModerator()) {
            return true;
        }
        
        if ($user->isInvestigator()) {
            return $report->assigned_to_user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can comment on the report.
     */
    public function comment(User $user, Report $report): bool
    {
        if ($user->isAdmin() || $user->isModerator()) {
            return true;
        }
        
        if ($user->isInvestigator()) {
            return $report->assigned_to_user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can assign the report.
     */
    public function assign(User $user, Report $report): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can change report status.
     */
    public function changeStatus(User $user, Report $report): bool
    {
        if ($user->isAdmin() || $user->isModerator()) {
            return true;
        }
        
        if ($user->isInvestigator()) {
            return $report->assigned_to_user_id === $user->id;
        }
        
        return false;
    }
}