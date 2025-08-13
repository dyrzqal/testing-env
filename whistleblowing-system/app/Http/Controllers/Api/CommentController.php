<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display comments for a report.
     */
    public function index(Request $request, Report $report): JsonResponse
    {
        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = $report->comments()->with('user:id,name,role');

        // Filter internal comments for non-admin users
        if (!$user->isAdmin()) {
            $query->where('is_internal', false);
        }

        $comments = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['comments' => $comments]);
    }

    /**
     * Store a new comment.
     */
    public function store(StoreCommentRequest $request, Report $report): JsonResponse
    {
        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $commentData = $request->validated();
        $commentData['report_id'] = $report->id;
        $commentData['user_id'] = $user->id;

        $comment = ReportComment::create($commentData);
        $comment->load('user:id,name,role');

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
        ], 201);
    }

    /**
     * Update a comment.
     */
    public function update(Request $request, Report $report, ReportComment $comment): JsonResponse
    {
        $request->validate([
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
            'is_internal' => ['sometimes', 'boolean']
        ]);

        $user = $request->user();
        
        // Can only edit own comments
        if ($comment->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->update($request->only(['comment', 'is_internal']));

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Report $report, ReportComment $comment): JsonResponse
    {
        $user = auth()->user();
        
        // Can only delete own comments or if admin
        if ($comment->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}