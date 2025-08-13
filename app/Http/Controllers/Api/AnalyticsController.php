<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get analytics overview.
     */
    public function overview(Request $request): JsonResponse
    {
        $dateRange = $this->getDateRange($request);
        
        $analytics = [
            'summary' => $this->getSummaryStats($dateRange),
            'status_distribution' => $this->getStatusDistribution($dateRange),
            'urgency_distribution' => $this->getUrgencyDistribution($dateRange),
            'category_performance' => $this->getCategoryPerformance($dateRange),
            'resolution_metrics' => $this->getResolutionMetrics($dateRange),
            'user_performance' => $this->getUserPerformance($dateRange),
        ];

        return response()->json(['analytics' => $analytics]);
    }

    /**
     * Get trend data.
     */
    public function trends(Request $request): JsonResponse
    {
        $period = $request->get('period', 'last_30_days');
        $groupBy = $request->get('group_by', 'day');

        $trends = [
            'reports_over_time' => $this->getReportsOverTime($period, $groupBy),
            'resolution_trends' => $this->getResolutionTrends($period, $groupBy),
            'urgency_trends' => $this->getUrgencyTrends($period, $groupBy),
        ];

        return response()->json(['trends' => $trends]);
    }

    /**
     * Get category statistics.
     */
    public function categoryStats(Request $request): JsonResponse
    {
        $dateRange = $this->getDateRange($request);
        
        $categoryStats = Category::withCount([
            'reports' => function ($query) use ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            },
            'reports as resolved_reports_count' => function ($query) use ($dateRange) {
                $query->whereIn('status', ['resolved'])
                      ->whereBetween('created_at', $dateRange);
            },
            'reports as pending_reports_count' => function ($query) use ($dateRange) {
                $query->whereNotIn('status', ['resolved', 'dismissed'])
                      ->whereBetween('created_at', $dateRange);
            }
        ])
        ->with([
            'reports' => function ($query) use ($dateRange) {
                $query->select(['id', 'category_id', 'status', 'urgency_level', 'created_at', 'resolved_at'])
                      ->whereBetween('created_at', $dateRange);
            }
        ])
        ->get()
        ->map(function ($category) {
            $avgResolutionTime = $category->reports
                ->whereNotNull('resolved_at')
                ->map(function ($report) {
                    return $report->created_at->diffInDays($report->resolved_at);
                })
                ->avg();

            return [
                'id' => $category->id,
                'name' => $category->name,
                'total_reports' => $category->reports_count,
                'resolved_reports' => $category->resolved_reports_count,
                'pending_reports' => $category->pending_reports_count,
                'resolution_rate' => $category->reports_count > 0 
                    ? round(($category->resolved_reports_count / $category->reports_count) * 100, 1) 
                    : 0,
                'avg_resolution_time' => round($avgResolutionTime ?? 0, 1),
            ];
        });

        return response()->json(['category_stats' => $categoryStats]);
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request): JsonResponse
    {
        $format = $request->get('format', 'json');
        $dateRange = $this->getDateRange($request);
        
        $exportData = [
            'date_range' => [
                'from' => $dateRange[0]->format('Y-m-d'),
                'to' => $dateRange[1]->format('Y-m-d'),
            ],
            'summary' => $this->getSummaryStats($dateRange),
            'detailed_reports' => $this->getDetailedReportsForExport($dateRange),
            'generated_at' => now()->toISOString(),
        ];

        // In a real application, you might generate CSV/Excel files here
        return response()->json([
            'message' => 'Export data generated successfully',
            'data' => $exportData,
            'download_url' => null // Would be a file URL in real implementation
        ]);
    }

    /**
     * Get date range from request.
     */
    private function getDateRange(Request $request): array
    {
        $from = $request->get('from', now()->subDays(30)->startOfDay());
        $to = $request->get('to', now()->endOfDay());

        if (is_string($from)) {
            $from = Carbon::parse($from)->startOfDay();
        }
        if (is_string($to)) {
            $to = Carbon::parse($to)->endOfDay();
        }

        return [$from, $to];
    }

    /**
     * Get summary statistics.
     */
    private function getSummaryStats(array $dateRange): array
    {
        $totalReports = Report::whereBetween('created_at', $dateRange)->count();
        $resolvedReports = Report::whereBetween('created_at', $dateRange)
            ->whereIn('status', ['resolved'])->count();
        $avgResolutionTime = Report::whereBetween('created_at', $dateRange)
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(DATEDIFF(resolved_at, created_at)) as avg_days')
            ->first()->avg_days ?? 0;

        return [
            'total_reports' => $totalReports,
            'resolved_reports' => $resolvedReports,
            'resolution_rate' => $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 1) : 0,
            'avg_resolution_time' => round($avgResolutionTime, 1),
            'anonymous_reports' => Report::whereBetween('created_at', $dateRange)
                ->where('is_anonymous', true)->count(),
        ];
    }

    /**
     * Get status distribution.
     */
    private function getStatusDistribution(array $dateRange): array
    {
        return Report::whereBetween('created_at', $dateRange)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get urgency distribution.
     */
    private function getUrgencyDistribution(array $dateRange): array
    {
        return Report::whereBetween('created_at', $dateRange)
            ->select('urgency_level', DB::raw('count(*) as count'))
            ->groupBy('urgency_level')
            ->get()
            ->pluck('count', 'urgency_level')
            ->toArray();
    }

    /**
     * Get category performance.
     */
    private function getCategoryPerformance(array $dateRange): array
    {
        return Category::withCount([
            'reports' => function ($query) use ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        ])
        ->having('reports_count', '>', 0)
        ->orderBy('reports_count', 'desc')
        ->take(10)
        ->get()
        ->map(function ($category) {
            return [
                'name' => $category->name,
                'reports_count' => $category->reports_count,
            ];
        })
        ->toArray();
    }

    /**
     * Get resolution metrics.
     */
    private function getResolutionMetrics(array $dateRange): array
    {
        $resolvedReports = Report::whereBetween('created_at', $dateRange)
            ->whereNotNull('resolved_at')
            ->selectRaw('
                AVG(DATEDIFF(resolved_at, created_at)) as avg_days,
                MIN(DATEDIFF(resolved_at, created_at)) as min_days,
                MAX(DATEDIFF(resolved_at, created_at)) as max_days
            ')
            ->first();

        return [
            'average_days' => round($resolvedReports->avg_days ?? 0, 1),
            'fastest_resolution' => $resolvedReports->min_days ?? 0,
            'slowest_resolution' => $resolvedReports->max_days ?? 0,
        ];
    }

    /**
     * Get user performance.
     */
    private function getUserPerformance(array $dateRange): array
    {
        return User::withCount([
            'assignedReports' => function ($query) use ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            },
            'assignedReports as resolved_count' => function ($query) use ($dateRange) {
                $query->whereBetween('created_at', $dateRange)
                      ->whereIn('status', ['resolved']);
            }
        ])
        ->where('role', 'investigator')
        ->having('assigned_reports_count', '>', 0)
        ->orderBy('resolved_count', 'desc')
        ->take(10)
        ->get()
        ->map(function ($user) {
            return [
                'name' => $user->name,
                'assigned_reports' => $user->assigned_reports_count,
                'resolved_reports' => $user->resolved_count,
                'resolution_rate' => $user->assigned_reports_count > 0 
                    ? round(($user->resolved_count / $user->assigned_reports_count) * 100, 1)
                    : 0,
            ];
        })
        ->toArray();
    }

    /**
     * Get reports over time.
     */
    private function getReportsOverTime(string $period, string $groupBy): array
    {
        $dateRange = $this->getPeriodDateRange($period);
        $dateFormat = $groupBy === 'month' ? '%Y-%m' : '%Y-%m-%d';

        return Report::whereBetween('created_at', $dateRange)
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Get resolution trends.
     */
    private function getResolutionTrends(string $period, string $groupBy): array
    {
        $dateRange = $this->getPeriodDateRange($period);
        $dateFormat = $groupBy === 'month' ? '%Y-%m' : '%Y-%m-%d';

        return Report::whereBetween('resolved_at', $dateRange)
            ->whereIn('status', ['resolved'])
            ->selectRaw("DATE_FORMAT(resolved_at, '$dateFormat') as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Get urgency trends.
     */
    private function getUrgencyTrends(string $period, string $groupBy): array
    {
        $dateRange = $this->getPeriodDateRange($period);
        $dateFormat = $groupBy === 'month' ? '%Y-%m' : '%Y-%m-%d';

        return Report::whereBetween('created_at', $dateRange)
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') as date, urgency_level, COUNT(*) as count")
            ->groupBy(['date', 'urgency_level'])
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($group) {
                return $group->pluck('count', 'urgency_level')->toArray();
            })
            ->toArray();
    }

    /**
     * Get period date range.
     */
    private function getPeriodDateRange(string $period): array
    {
        return match($period) {
            'last_7_days' => [now()->subDays(7), now()],
            'last_30_days' => [now()->subDays(30), now()],
            'last_90_days' => [now()->subDays(90), now()],
            'last_year' => [now()->subYear(), now()],
            default => [now()->subDays(30), now()],
        };
    }

    /**
     * Get detailed reports for export.
     */
    private function getDetailedReportsForExport(array $dateRange): array
    {
        return Report::with(['category', 'assignedToUser'])
            ->whereBetween('created_at', $dateRange)
            ->get()
            ->map(function ($report) {
                return [
                    'reference_number' => $report->reference_number,
                    'title' => $report->title,
                    'category' => $report->category->name,
                    'status' => $report->status,
                    'urgency_level' => $report->urgency_level,
                    'is_anonymous' => $report->is_anonymous,
                    'assigned_to' => $report->assignedToUser?->name,
                    'submitted_at' => $report->submitted_at?->format('Y-m-d H:i:s'),
                    'resolved_at' => $report->resolved_at?->format('Y-m-d H:i:s'),
                    'resolution_time_days' => $report->resolved_at 
                        ? $report->submitted_at->diffInDays($report->resolved_at)
                        : null,
                ];
            })
            ->toArray();
    }
}