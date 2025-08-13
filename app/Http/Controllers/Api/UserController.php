<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->makeHidden(['password'])
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['assignedReports' => function ($query) {
            $query->select(['id', 'title', 'status', 'assigned_to_user_id', 'created_at'])
                  ->latest()
                  ->take(5);
        }]);

        return response()->json(['user' => $user->makeHidden(['password'])]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $userData = $request->validated();
        
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->makeHidden(['password'])
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'Cannot delete your own account'
            ], 422);
        }

        // Check if user has assigned reports
        if ($user->assignedReports()->exists()) {
            return response()->json([
                'error' => 'Cannot delete user',
                'message' => 'This user has assigned reports. Please reassign them before deleting.'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(User $user): JsonResponse
    {
        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'Cannot deactivate your own account'
            ], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user->makeHidden(['password'])
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $newPassword = Str::random(12);
        
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // In a real application, you would send this password via email
        return response()->json([
            'message' => 'Password reset successfully',
            'temporary_password' => $newPassword
        ]);
    }

    /**
     * Get list of investigators for assignment.
     */
    public function investigators(): JsonResponse
    {
        $investigators = User::where('role', 'investigator')
            ->where('is_active', true)
            ->select(['id', 'name', 'email', 'department'])
            ->orderBy('name')
            ->get();

        return response()->json(['investigators' => $investigators]);
    }
}