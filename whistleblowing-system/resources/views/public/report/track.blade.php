@extends('layouts.public')

@section('title', 'Track Your Report')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Track Your Report
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Enter your reference number to check the status of your report.
            </p>
        </div>

        <!-- Track Form -->
        <div class="bg-white shadow-lg rounded-lg">
            <form action="{{ route('public.report.track.submit') }}" method="POST" class="p-6">
                @csrf
                
                <div class="mb-6">
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Reference Number <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-500 mb-4">
                        Enter the reference number you received after submitting your report (e.g., WB-ABC12345)
                    </p>
                    <input 
                        type="text" 
                        name="reference_number" 
                        id="reference_number" 
                        required 
                        value="{{ old('reference_number') }}" 
                        placeholder="WB-" 
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reference_number') border-red-300 @enderror"
                        style="text-transform: uppercase;"
                        oninput="this.value = this.value.toUpperCase()"
                    >
                    @error('reference_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Track Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        About Report Tracking
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Your reference number was provided after you submitted your report</li>
                            <li>You can track the status and any public updates about your case</li>
                            <li>All tracking information maintains your anonymity</li>
                            <li>If you lost your reference number, you cannot recover it for security reasons</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Frequently Asked Questions</h3>
            </div>
            <div class="px-6 py-4 space-y-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">How long does the investigation process take?</h4>
                    <p class="mt-1 text-sm text-gray-600">Investigation timelines vary depending on the complexity and urgency of the case. Simple matters may be resolved within days, while complex investigations can take several weeks.</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900">What do the different statuses mean?</h4>
                    <div class="mt-1 text-sm text-gray-600">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Submitted:</strong> Your report has been received and is awaiting initial review</li>
                            <li><strong>Under Review:</strong> Your report is being assessed for priority and assignment</li>
                            <li><strong>Investigating:</strong> An investigator has been assigned and is actively working on your case</li>
                            <li><strong>Requires More Info:</strong> Additional information may be needed to proceed</li>
                            <li><strong>Resolved:</strong> The investigation has been completed and appropriate action taken</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Can I add additional information to my report?</h4>
                    <p class="mt-1 text-sm text-gray-600">If you have additional information, you can submit a new report and reference your original case number in the description.</p>
                </div>
            </div>
        </div>

        <!-- Action Links -->
        <div class="mt-8 text-center space-y-4">
            <div class="space-x-4">
                <a href="{{ route('public.report.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Submit New Report
                </a>
                <a href="{{ route('public.report.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
    <div class="max-w-sm w-full bg-red-600 shadow-lg rounded-lg pointer-events-auto" x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="rounded-lg shadow-xs overflow-hidden">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm leading-5 font-medium text-white">
                            {{ session('error') }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-red-200 hover:text-white focus:outline-none focus:text-white transition ease-in-out duration-150">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection