<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\ReportAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['category', 'assignedToUser', 'attachments'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by urgency
        if ($request->has('urgency_level')) {
            $query->where('urgency_level', $request->urgency_level);
        }

        // Filter by assigned user
        if ($request->has('assigned_to')) {
            $query->where('assigned_to_user_id', $request->assigned_to);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Role-based filtering
        $user = $request->user();
        if ($user->role === 'investigator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        $reports = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'reports' => $reports->items(),
            'pagination' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ]
        ]);
    }

    /**
     * Store a newly created report.
     */
    public function store(StoreReportRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $reportData = $request->validated();
            $reportData['submitted_at'] = now();

            $report = Report::create($reportData);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('attachments', $filename, 'private');

                    ReportAttachment::create([
                        'report_id' => $report->id,
                        'filename' => $file->getClientOriginalName(),
                        'filepath' => $path,
                        'filesize' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]);
                }
            }

            DB::commit();

            $report->load(['category', 'attachments']);

            return response()->json([
                'message' => 'Report submitted successfully',
                'report' => $report,
                'reference_number' => $report->reference_number
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Failed to submit report',
                'message' => 'An error occurred while processing your report. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified report.
     */
    public function show(Request $request, Report $report): JsonResponse
    {
        $user = $request->user();
        
        // Role-based access control
        if ($user && $user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $report->load(['category', 'assignedToUser', 'attachments', 'comments.user']);

        return response()->json(['report' => $report]);
    }

    /**
     * Update the specified report.
     */
    public function update(UpdateReportRequest $request, Report $report): JsonResponse
    {
        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $report->update($request->validated());
        $report->load(['category', 'assignedToUser', 'attachments']);

        return response()->json([
            'message' => 'Report updated successfully',
            'report' => $report
        ]);
    }

    /**
     * Update report status.
     */
    public function updateStatus(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:submitted,under_review,investigating,requires_more_info,resolved,dismissed'],
            'resolution_details' => ['nullable', 'string', 'max:2000']
        ]);

        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $updateData = ['status' => $request->status];

        // Set timestamps based on status
        switch ($request->status) {
            case 'under_review':
                if (!$report->reviewed_at) {
                    $updateData['reviewed_at'] = now();
                }
                break;
            case 'resolved':
            case 'dismissed':
                $updateData['resolved_at'] = now();
                if ($request->resolution_details) {
                    $updateData['resolution_details'] = $request->resolution_details;
                }
                break;
        }

        $report->update($updateData);

        return response()->json([
            'message' => 'Report status updated successfully',
            'report' => $report
        ]);
    }

    /**
     * Assign report to user.
     */
    public function assign(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'assigned_to_user_id' => ['nullable', 'exists:users,id']
        ]);

        $user = $request->user();
        
        // Only admins and moderators can assign reports
        if (!in_array($user->role, ['admin', 'moderator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $report->update([
            'assigned_to_user_id' => $request->assigned_to_user_id
        ]);

        $report->load(['assignedToUser']);

        return response()->json([
            'message' => 'Report assigned successfully',
            'report' => $report
        ]);
    }

    /**
     * Remove the specified report.
     */
    public function destroy(Report $report): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Delete attachments from storage
            foreach ($report->attachments as $attachment) {
                Storage::disk('private')->delete($attachment->filepath);
                $attachment->delete();
            }

            // Delete comments
            $report->comments()->delete();

            // Delete the report
            $report->delete();

            DB::commit();

            return response()->json([
                'message' => 'Report deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Failed to delete report',
                'message' => 'An error occurred while deleting the report.'
            ], 500);
        }
    }

    /**
     * Track a report by reference number (public endpoint).
     */
    public function track(string $reference): JsonResponse
    {
        $report = Report::where('reference_number', $reference)
            ->with(['category'])
            ->first();

        if (!$report) {
            return response()->json([
                'error' => 'Report not found',
                'message' => 'No report found with the provided reference number.'
            ], 404);
        }

        // Return limited information for public tracking
        return response()->json([
            'report' => [
                'reference_number' => $report->reference_number,
                'title' => $report->title,
                'category' => $report->category->name,
                'status' => $report->status,
                'urgency_level' => $report->urgency_level,
                'submitted_at' => $report->submitted_at,
                'reviewed_at' => $report->reviewed_at,
                'resolved_at' => $report->resolved_at,
            ]
        ]);
    }
}