<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $stats = [
            'total_reports' => $this->getTotalReports($user),
            'pending_reports' => $this->getPendingReports($user),
            'resolved_reports' => $this->getResolvedReports($user),
            'urgent_reports' => $this->getUrgentReports($user),
            'my_assigned_reports' => $this->getMyAssignedReports($user),
            'reports_by_status' => $this->getReportsByStatus($user),
            'reports_by_urgency' => $this->getReportsByUrgency($user),
            'recent_activity' => $this->getRecentActivity($user),
        ];

        // Admin/Moderator specific stats
        if (in_array($user->role, ['admin', 'moderator'])) {
            $stats['total_users'] = User::count();
            $stats['active_users'] = User::where('is_active', true)->count();
            $stats['total_categories'] = Category::count();
            $stats['reports_this_month'] = $this->getReportsThisMonth();
            $stats['avg_resolution_time'] = $this->getAverageResolutionTime();
        }

        return response()->json(['stats' => $stats]);
    }

    /**
     * Get recent reports.
     */
    public function recentReports(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Report::with(['category', 'assignedToUser'])
            ->orderBy('created_at', 'desc');

        // Role-based filtering
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        $reports = $query->take(10)->get();

        return response()->json(['recent_reports' => $reports]);
    }

    /**
     * Get user's assigned reports.
     */
    public function myAssignedReports(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $reports = Report::with(['category'])
            ->where('assigned_to_user_id', $user->id)
            ->whereNotIn('status', ['resolved', 'dismissed'])
            ->orderBy('urgency_level', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return response()->json(['my_reports' => $reports]);
    }

    /**
     * Get total reports count based on user role.
     */
    private function getTotalReports(User $user): int
    {
        $query = Report::query();
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->count();
    }

    /**
     * Get pending reports count.
     */
    private function getPendingReports(User $user): int
    {
        $query = Report::whereIn('status', ['submitted', 'under_review', 'investigating']);
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->count();
    }

    /**
     * Get resolved reports count.
     */
    private function getResolvedReports(User $user): int
    {
        $query = Report::whereIn('status', ['resolved', 'dismissed']);
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->count();
    }

    /**
     * Get urgent reports count.
     */
    private function getUrgentReports(User $user): int
    {
        $query = Report::whereIn('urgency_level', ['high', 'critical'])
            ->whereNotIn('status', ['resolved', 'dismissed']);
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->count();
    }

    /**
     * Get user's assigned reports count.
     */
    private function getMyAssignedReports(User $user): int
    {
        return Report::where('assigned_to_user_id', $user->id)
            ->whereNotIn('status', ['resolved', 'dismissed'])
            ->count();
    }

    /**
     * Get reports by status.
     */
    private function getReportsByStatus(User $user): array
    {
        $query = Report::select('status', DB::raw('count(*) as count'))
            ->groupBy('status');
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->get()->pluck('count', 'status')->toArray();
    }

    /**
     * Get reports by urgency.
     */
    private function getReportsByUrgency(User $user): array
    {
        $query = Report::select('urgency_level', DB::raw('count(*) as count'))
            ->groupBy('urgency_level');
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->get()->pluck('count', 'urgency_level')->toArray();
    }

    /**
     * Get recent activity.
     */
    private function getRecentActivity(User $user): array
    {
        $query = Report::select(['id', 'title', 'status', 'updated_at'])
            ->orderBy('updated_at', 'desc');
        
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        return $query->take(5)->get()->toArray();
    }

    /**
     * Get reports count for this month.
     */
    private function getReportsThisMonth(): int
    {
        return Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    /**
     * Get average resolution time in days.
     */
    private function getAverageResolutionTime(): float
    {
        $resolvedReports = Report::whereNotNull('resolved_at')
            ->whereNotNull('submitted_at')
            ->select(DB::raw('AVG(DATEDIFF(resolved_at, submitted_at)) as avg_days'))
            ->first();

        return round($resolvedReports->avg_days ?? 0, 1);
    }
}