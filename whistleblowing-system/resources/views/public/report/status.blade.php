@extends('layouts.public')

@section('title', 'Report Status - ' . $trackingData['reference_number'])

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Report Status
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Reference: <code class="text-red-600 font-mono font-bold">{{ $trackingData['reference_number'] }}</code>
            </p>
        </div>

        <!-- Status Overview -->
        <div class="bg-white shadow-lg rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Current Status</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-medium text-gray-900">{{ $trackingData['title'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $trackingData['category'] }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'under_review' => 'bg-yellow-100 text-yellow-800',
                                'investigating' => 'bg-purple-100 text-purple-800',
                                'requires_more_info' => 'bg-orange-100 text-orange-800',
                                'resolved' => 'bg-green-100 text-green-800',
                                'dismissed' => 'bg-gray-100 text-gray-800'
                            ];
                            $statusClass = $statusColors[$trackingData['status']] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $trackingData['status'])) }}
                        </span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-400 rounded-full"></div>
                        <div class="ml-4 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">Report Submitted</p>
                            <p class="text-sm text-gray-500">{{ $trackingData['submitted_at']->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($trackingData['status'] !== 'submitted')
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-2 h-2 bg-yellow-400 rounded-full"></div>
                        <div class="ml-4 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">Under Review</p>
                            <p class="text-sm text-gray-500">Your report is being assessed for priority and assignment</p>
                        </div>
                    </div>
                    @endif

                    @if(in_array($trackingData['status'], ['investigating', 'requires_more_info', 'resolved', 'dismissed']))
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-2 h-2 bg-purple-400 rounded-full"></div>
                        <div class="ml-4 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">Investigation Started</p>
                            <p class="text-sm text-gray-500">An investigator has been assigned to your case</p>
                        </div>
                    </div>
                    @endif

                    @if(in_array($trackingData['status'], ['resolved', 'dismissed']))
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-400 rounded-full"></div>
                        <div class="ml-4 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $trackingData['status'] === 'resolved' ? 'Case Resolved' : 'Case Closed' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $trackingData['status'] === 'resolved' ? 'Investigation completed and appropriate action taken' : 'Case has been reviewed and closed' }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Report Details -->
        <div class="bg-white shadow-lg rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Report Information</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $trackingData['category'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $trackingData['submitted_at']->format('M j, Y \a\t g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $trackingData['last_updated']->format('M j, Y \a\t g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reference Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $trackingData['reference_number'] }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Title</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $trackingData['title'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Public Updates -->
        @if($publicComments->count() > 0)
        <div class="bg-white shadow-lg rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Updates</h2>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($publicComments as $comment)
                    <div class="border-l-4 border-blue-400 pl-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">{{ $comment->comment }}</p>
                                @if($comment->isStatusChange() && $comment->status_change)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Status changed to: {{ ucwords(str_replace('_', ' ', $comment->status_change['new_status'] ?? 'Unknown')) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <p class="text-xs text-gray-500">{{ $comment->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Status Explanations -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Status Meanings</h3>
            <dl class="space-y-2">
                <div class="flex">
                    <dt class="font-medium text-blue-800 w-32">Submitted:</dt>
                    <dd class="text-blue-700">Report received and awaiting initial review</dd>
                </div>
                <div class="flex">
                    <dt class="font-medium text-blue-800 w-32">Under Review:</dt>
                    <dd class="text-blue-700">Being assessed for priority and investigator assignment</dd>
                </div>
                <div class="flex">
                    <dt class="font-medium text-blue-800 w-32">Investigating:</dt>
                    <dd class="text-blue-700">Active investigation in progress</dd>
                </div>
                <div class="flex">
                    <dt class="font-medium text-blue-800 w-32">Requires Info:</dt>
                    <dd class="text-blue-700">Additional information needed to proceed</dd>
                </div>
                <div class="flex">
                    <dt class="font-medium text-blue-800 w-32">Resolved:</dt>
                    <dd class="text-blue-700">Investigation complete, appropriate action taken</dd>
                </div>
            </dl>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <div class="space-x-4">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Status
                </button>
                <a href="{{ route('public.report.track') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Track Another Report
                </a>
            </div>
            <div class="space-x-4">
                <a href="{{ route('public.report.create') }}" class="text-sm text-red-600 hover:text-red-500">Submit New Report</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('public.report.index') }}" class="text-sm text-gray-600 hover:text-gray-500">Return Home</a>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Keep Your Reference Number Safe
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Save your reference number ({{ $trackingData['reference_number'] }}) securely. This is the only way to track your report's progress, and it cannot be recovered if lost.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection