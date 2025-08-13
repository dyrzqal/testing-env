<!-- Admin Dashboard -->
<div class="space-y-6">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Reports</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($dashboardData['totalReports']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Users</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($dashboardData['totalUsers']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Reports</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($dashboardData['pendingReports']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Users</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($dashboardData['activeUsers']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Reports by Status -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reports by Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pending</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dashboardData['pendingReports'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">In Progress</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dashboardData['inProgressReports'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Resolved</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dashboardData['resolvedReports'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rejected</span>
                        <span class="text-sm font-medium text-gray-900">{{ $dashboardData['rejectedReports'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users by Role -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Users by Role</h3>
                <div class="space-y-3">
                    @foreach($dashboardData['usersByRole'] as $role => $count)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ ucfirst($role) }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Reports</h3>
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($dashboardData['recentReports'] as $report)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($report->title, 40) }}</p>
                                <p class="text-xs text-gray-500">{{ $report->category->name ?? 'No Category' }} • {{ $report->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($report->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No recent reports</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($dashboardData['recentUsers'] as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-sm font-medium text-white"
                                         style="background-color: {{ $user->role_color }};">
                                        {{ $user->initials }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($user->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No recent users</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.reports.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Create Report</p>
                        <p class="text-xs text-gray-500">Add new report manually</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-6 w-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Add User</p>
                        <p class="text-xs text-gray-500">Create new user account</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.analytics.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-6 w-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">View Analytics</p>
                        <p class="text-xs text-gray-500">Detailed reports & insights</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>