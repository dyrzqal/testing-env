<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Report Details') }} - {{ $report->reference_number }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to Reports') }}
                </a>
                @can('update', $report)
                <a href="{{ route('admin.reports.edit', $report) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit Report') }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Report Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $report->title }}</h1>
                            <p class="text-gray-600">{{ $report->description }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Reference</div>
                            <div class="text-lg font-mono font-bold text-gray-900">{{ $report->reference_number }}</div>
                        </div>
                    </div>
                    
                    <!-- Status and Urgency Badges -->
                    <div class="flex space-x-4 mt-4">
                        <div>
                            <span class="text-sm text-gray-500">Status:</span>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                  style="background-color: {{ $report->status_color }}20; color: {{ $report->status_color }};">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Urgency:</span>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                  style="background-color: {{ $report->urgency_color }}20; color: {{ $report->urgency_color }};">
                                {{ ucfirst($report->urgency_level) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Days Open:</span>
                            <span class="ml-2 text-sm font-medium text-gray-900">{{ $report->days_open }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Incident Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Incident Details</h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->incident_location }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->incident_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->incident_time->format('H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1">
                                        @if($report->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: {{ $report->category->color }}20; color: {{ $report->category->color }};">
                                                {{ $report->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">No Category</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($report->attachments->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Attachments</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($report->attachments as $attachment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $attachment->file_name }}</p>
                                                <p class="text-sm text-gray-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.reports.attachments.download', ['report' => $report, 'attachment' => $attachment]) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Comments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Comments & Updates</h3>
                        </div>
                        <div class="p-6">
                            @if($report->comments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($report->comments as $comment)
                                    <div class="border-l-4 border-gray-200 pl-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $comment->user->initials }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <p class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</p>
                                                    <span class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                                                </div>
                                                <p class="text-sm text-gray-700 mt-1">{{ $comment->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No comments yet</p>
                            @endif

                            <!-- Add Comment Form -->
                            @can('comment', $report)
                            <div class="mt-6 border-t border-gray-200 pt-6">
                                <form method="POST" action="{{ route('admin.reports.comments.store', $report) }}">
                                    @csrf
                                    <div>
                                        <label for="comment" class="block text-sm font-medium text-gray-700">Add Comment</label>
                                        <textarea name="comment" id="comment" rows="3"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Add your comment or update..."
                                                  required></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            {{ __('Add Comment') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Status Update -->
                    @can('changeStatus', $report)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Update Status</h3>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.reports.status.update', $report) }}">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">New Status</label>
                                        <select name="status" id="status" 
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="under_review" {{ $report->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                            <option value="investigating" {{ $report->status == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                            <option value="requires_more_info" {{ $report->status == 'requires_more_info' ? 'selected' : '' }}>Requires More Info</option>
                                            <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="dismissed" {{ $report->status == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="resolution_details" class="block text-sm font-medium text-gray-700">Resolution Details</label>
                                        <textarea name="resolution_details" id="resolution_details" rows="3"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Provide resolution details if applicable...">{{ $report->resolution_details }}</textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Update Status') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endcan

                    <!-- Assignment -->
                    @can('assign', $report)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Assignment</h3>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.reports.update', $report) }}">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label for="assigned_to_user_id" class="block text-sm font-medium text-gray-700">Assign To</label>
                                        <select name="assigned_to_user_id" id="assigned_to_user_id" 
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Unassigned</option>
                                            @foreach($investigators as $investigator)
                                                <option value="{{ $investigator->id }}" {{ $report->assigned_to_user_id == $investigator->id ? 'selected' : '' }}>
                                                    {{ $investigator->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Update Assignment') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endcan

                    <!-- Report Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Report Information</h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->submitted_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @if($report->reviewed_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reviewed</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->reviewed_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @endif
                                @if($report->resolved_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Resolved</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->resolved_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @endif
                                @if($report->assignedToUser)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->assignedToUser->name }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>