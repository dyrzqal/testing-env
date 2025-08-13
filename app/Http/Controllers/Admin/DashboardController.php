<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get dashboard data based on user role
        $dashboardData = $this->getDashboardData($user);
        
        return view('admin.dashboard', compact('dashboardData', 'user'));
    }

    public function analytics()
    {
        $this->authorize('viewAnalytics');
        
        $analyticsData = $this->getAnalyticsData();
        
        return view('admin.analytics', compact('analyticsData'));
    }

    public function export()
    {
        $this->authorize('exportData');
        
        // Implementation for data export
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    private function getDashboardData($user)
    {
        $data = [];
        
        switch ($user->role) {
            case 'admin':
                $data = $this->getAdminDashboardData();
                break;
            case 'moderator':
                $data = $this->getModeratorDashboardData();
                break;
            case 'investigator':
                $data = $this->getInvestigatorDashboardData($user);
                break;
        }
        
        return $data;
    }

    private function getAdminDashboardData()
    {
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $inProgressReports = Report::where('status', 'in_progress')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $rejectedReports = Report::where('status', 'rejected')->count();
        
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
        
        $reportsByStatus = Report::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $recentReports = Report::with(['category', 'assignedTo'])
            ->latest()
            ->take(5)
            ->get();
        
        $recentUsers = User::latest()
            ->take(5)
            ->get();
        
        $monthlyReports = Report::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        return [
            'totalReports' => $totalReports,
            'pendingReports' => $pendingReports,
            'inProgressReports' => $inProgressReports,
            'resolvedReports' => $resolvedReports,
            'rejectedReports' => $rejectedReports,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'usersByRole' => $usersByRole,
            'reportsByStatus' => $reportsByStatus,
            'recentReports' => $recentReports,
            'recentUsers' => $recentUsers,
            'monthlyReports' => $monthlyReports,
        ];
    }

    private function getModeratorDashboardData()
    {
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $inProgressReports = Report::where('status', 'in_progress')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $rejectedReports = Report::where('status', 'rejected')->count();
        
        $reportsByCategory = Report::with('category')
            ->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->get();
        
        $recentReports = Report::with(['category', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();
        
        $monthlyReports = Report::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        return [
            'totalReports' => $totalReports,
            'pendingReports' => $pendingReports,
            'inProgressReports' => $inProgressReports,
            'resolvedReports' => $resolvedReports,
            'rejectedReports' => $rejectedReports,
            'reportsByCategory' => $reportsByCategory,
            'recentReports' => $recentReports,
            'monthlyReports' => $monthlyReports,
        ];
    }

    private function getInvestigatorDashboardData($user)
    {
        $assignedReports = Report::where('assigned_to_user_id', $user->id)->count();
        $pendingAssigned = Report::where('assigned_to_user_id', $user->id)
            ->where('status', 'pending')->count();
        $inProgressAssigned = Report::where('assigned_to_user_id', $user->id)
            ->where('status', 'in_progress')->count();
        $resolvedAssigned = Report::where('assigned_to_user_id', $user->id)
            ->where('status', 'resolved')->count();
        
        $myReports = Report::where('assigned_to_user_id', $user->id)
            ->with(['category', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();
        
        $monthlyAssigned = Report::where('assigned_to_user_id', $user->id)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        return [
            'assignedReports' => $assignedReports,
            'pendingAssigned' => $pendingAssigned,
            'inProgressAssigned' => $inProgressAssigned,
            'resolvedAssigned' => $resolvedAssigned,
            'myReports' => $myReports,
            'monthlyAssigned' => $monthlyAssigned,
        ];
    }

    private function getAnalyticsData()
    {
        $totalReports = Report::count();
        $reportsThisMonth = Report::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        
        $reportsLastMonth = Report::whereMonth('created_at', date('m', strtotime('-1 month')))
            ->whereYear('created_at', date('Y', strtotime('-1 month')))
            ->count();
        
        $monthlyGrowth = $reportsLastMonth > 0 
            ? (($reportsThisMonth - $reportsLastMonth) / $reportsLastMonth) * 100 
            : 0;
        
        $reportsByStatus = Report::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $reportsByCategory = Report::with('category')
            ->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->get();
        
        $dailyReports = Report::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'totalReports' => $totalReports,
            'reportsThisMonth' => $reportsThisMonth,
            'reportsLastMonth' => $reportsLastMonth,
            'monthlyGrowth' => round($monthlyGrowth, 2),
            'reportsByStatus' => $reportsByStatus,
            'reportsByCategory' => $reportsByCategory,
            'dailyReports' => $dailyReports,
        ];
    }
}
