<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Category;
use App\Models\User;
use App\Models\ReportComment;
use App\Models\ReportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Report::with(['category', 'assignedToUser', 'attachments']);
        
        // Filter based on user role
        if ($user->isInvestigator()) {
            $query->where('assigned_to_user_id', $user->id);
        }
        
        $reports = $query->latest()->paginate(15);
        $categories = Category::all();
        $investigators = User::where('role', 'investigator')->where('is_active', true)->get();
        
        return view('admin.reports.index', compact('reports', 'categories', 'investigators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $investigators = User::where('role', 'investigator')->where('is_active', true)->get();
        
        return view('admin.reports.create', compact('categories', 'investigators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'incident_location' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $report = Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'incident_location' => $request->incident_location,
            'incident_date' => $request->incident_date,
            'incident_time' => $request->incident_time,
            'urgency_level' => $request->urgency_level,
            'assigned_to_user_id' => $request->assigned_to_user_id,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('report-attachments', 'public');
                $report->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $this->authorize('view', $report);
        
        $report->load(['category', 'assignedToUser', 'attachments', 'comments.user']);
        $investigators = User::where('role', 'investigator')->where('is_active', true)->get();
        
        return view('admin.reports.show', compact('report', 'investigators'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        $this->authorize('update', $report);
        
        $categories = Category::all();
        $investigators = User::where('role', 'investigator')->where('is_active', true)->get();
        
        return view('admin.reports.edit', compact('report', 'categories', 'investigators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'incident_location' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $report->update($request->only([
            'title', 'description', 'category_id', 'incident_location',
            'incident_date', 'incident_time', 'urgency_level', 'assigned_to_user_id'
        ]));

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        
        // Delete attachments
        foreach ($report->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        $report->delete();
        
        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * Update report status
     */
    public function updateStatus(Request $request, Report $report)
    {
        $this->authorize('update', $report);
        
        $request->validate([
            'status' => 'required|in:pending,under_review,investigating,requires_more_info,resolved,dismissed',
            'resolution_details' => 'nullable|string'
        ]);

        $report->update([
            'status' => $request->status,
            'resolution_details' => $request->resolution_details,
        ]);

        if ($request->status === 'resolved') {
            $report->update(['resolved_at' => now()]);
        } elseif ($request->status === 'under_review') {
            $report->update(['reviewed_at' => now()]);
        }

        return back()->with('success', 'Report status updated successfully.');
    }

    /**
     * Add comment to report
     */
    public function addComment(Request $request, Report $report)
    {
        $this->authorize('comment', $report);
        
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $report->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(Report $report, ReportAttachment $attachment)
    {
        $this->authorize('view', $report);
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Categories management
     */
    public function categories()
    {
        $this->authorize('manageCategories');
        
        $categories = Category::withCount('reports')->paginate(15);
        
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $this->authorize('manageCategories');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7'
        ]);

        Category::create($request->all());
        
        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->authorize('manageCategories');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7'
        ]);

        $category->update($request->all());
        
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category)
    {
        $this->authorize('manageCategories');
        
        if ($category->reports()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing reports.');
        }
        
        $category->delete();
        
        return back()->with('success', 'Category deleted successfully.');
    }

    /**
     * Users management
     */
    public function users()
    {
        $this->authorize('manageUsers');
        
        $users = User::withCount('assignedReports')->paginate(15);
        $roles = ['admin', 'moderator', 'investigator'];
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $this->authorize('manageUsers');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,moderator,investigator',
            'department' => 'nullable|string|max:255'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'is_active' => true,
        ]);
        
        return back()->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->authorize('manageUsers');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,moderator,investigator',
            'department' => 'nullable|string|max:255'
        ]);

        $user->update($request->all());
        
        return back()->with('success', 'User updated successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        $this->authorize('manageUsers');
        
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}
