@extends('layouts.public')

@section('title', 'Report Submitted Successfully')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">
                Report Submitted Successfully
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Thank you for speaking up. Your report has been received and will be reviewed by our team.
            </p>
        </div>

        <!-- Reference Number Card -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <div class="text-center">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Reference Number</h2>
                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <code class="text-2xl font-mono font-bold text-red-600">{{ $report->reference_number }}</code>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Important: Save this reference number
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>This is the only way to track your report's progress. Save it in a secure location.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="copyToClipboard('{{ $report->reference_number }}')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copy Reference Number
                </button>
            </div>
        </div>

        <!-- Report Details -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Summary</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $report->category->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Urgency Level</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $report->urgency_color }}20; color: {{ $report->urgency_color }}">
                            {{ ucfirst($report->urgency_level) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Submission Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $report->is_anonymous ? 'Anonymous' : 'With Contact Information' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Submitted On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $report->submitted_at->format('M j, Y \a\t g:i A') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $report->title }}</dd>
                </div>
            </dl>
        </div>

        <!-- Next Steps -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">What Happens Next?</h3>
            <div class="space-y-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                            <span class="text-sm font-medium">1</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Initial Review</h4>
                        <p class="text-sm text-gray-500">Your report will be reviewed within 24-48 hours to assess urgency and assign to appropriate personnel.</p>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                            <span class="text-sm font-medium">2</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Investigation</h4>
                        <p class="text-sm text-gray-500">Our trained investigators will conduct a thorough and confidential investigation.</p>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                            <span class="text-sm font-medium">3</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Resolution</h4>
                        <p class="text-sm text-gray-500">Appropriate action will be taken based on the investigation findings, and you can track progress using your reference number.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <div class="space-x-4">
                <a href="{{ route('public.report.track') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Track This Report
                </a>
                <a href="{{ route('public.report.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Return Home
                </a>
            </div>
            <p class="text-sm text-gray-500">
                Need to submit another report? 
                <a href="{{ route('public.report.create') }}" class="text-red-600 hover:text-red-500">Click here</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Copied!';
        button.disabled = true;
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Reference number copied to clipboard!');
    });
}
</script>
@endpush
@endsection