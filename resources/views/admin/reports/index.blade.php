<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reports Management') }}
            </h2>
            @can('create', App\Models\Report::class)
            <a href="{{ route('admin.reports.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Create New Report') }}
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters and Search -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" name="search" id="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by title, description..."
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" id="category" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                    <option value="requires_more_info" {{ request('status') == 'requires_more_info' ? 'selected' : '' }}>Requires More Info</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
                                <select name="urgency" id="urgency" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Urgency</option>
                                    <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ request('urgency') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-between">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Filter') }}
                            </button>
                            <a href="{{ route('admin.reports.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                {{ __('Clear Filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reports List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($reports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reference
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Urgency
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Assigned To
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Submitted
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $report->reference_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="max-w-xs truncate" title="{{ $report->title }}">
                                                    {{ $report->title }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($report->category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                          style="background-color: {{ $report->category->color }}20; color: {{ $report->category->color }};">
                                                        {{ $report->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">No Category</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      style="background-color: {{ $report->status_color }}20; color: {{ $report->status_color }};">
                                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      style="background-color: {{ $report->urgency_color }}20; color: {{ $report->urgency_color }};">
                                                    {{ ucfirst($report->urgency_level) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($report->assignedToUser)
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8">
                                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                                <span class="text-sm font-medium text-gray-700">
                                                                    {{ $report->assignedToUser->initials }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $report->assignedToUser->name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ $report->assignedToUser->department ?? 'No Department' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $report->submitted_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.reports.show', $report) }}" 
                                                       class="text-blue-600 hover:text-blue-900">View</a>
                                                    @can('update', $report)
                                                    <a href="{{ route('admin.reports.edit', $report) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    @endcan
                                                    @can('delete', $report)
                                                    <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" 
                                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new report.</p>
                            @can('create', App\Models\Report::class)
                            <div class="mt-6">
                                <a href="{{ route('admin.reports.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    {{ __('Create New Report') }}
                                </a>
                            </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>