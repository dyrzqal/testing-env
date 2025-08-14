<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manageUsers');
        
        $users = User::withCount('assignedReports')->paginate(15);
        $roles = ['admin', 'moderator', 'investigator'];
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manageUsers');
        
        $roles = ['admin', 'moderator', 'investigator'];
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manageUsers');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,moderator,investigator',
            'department' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('manageUsers');
        
        $user->load(['assignedReports' => function($query) {
            $query->latest()->paginate(10);
        }, 'reportComments' => function($query) {
            $query->latest()->paginate(10);
        }]);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('manageUsers');
        
        $roles = ['admin', 'moderator', 'investigator'];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('manageUsers');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,moderator,investigator',
            'department' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department' => $request->department,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('manageUsers');
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        if ($user->assignedReports()->count() > 0) {
            return back()->with('error', 'Cannot delete user with assigned reports.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $this->authorize('manageUsers');
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }
        
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, User $user)
    {
        $this->authorize('manageUsers');
        
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $user->update($request->only(['name', 'department']));
        
        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Password updated successfully.');
    }
}