@extends('layouts.public')

@section('title', 'Report Misconduct Anonymously')

@section('content')
<div class="bg-gradient-to-r from-red-600 to-red-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                Speak Up. Stay Anonymous.
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-red-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Report unethical behavior, misconduct, or violations confidentially. Your identity is protected, and every report is taken seriously.
            </p>
            <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                <div class="rounded-md shadow">
                    <a href="{{ route('public.report.create') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-red-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                        Submit a Report
                    </a>
                </div>
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                    <a href="{{ route('public.report.track') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-500 hover:bg-red-400 md:py-4 md:text-lg md:px-10">
                        Track Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-red-600 font-semibold tracking-wide uppercase">Protected Reporting</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Your Safety is Our Priority
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                We provide a secure platform for reporting misconduct while ensuring your anonymity and protection.
            </p>
        </div>

        <div class="mt-16">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Anonymous & Secure</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Your identity is protected with state-of-the-art encryption. Report without fear of retaliation.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Professional Investigation</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Every report is reviewed by trained professionals and investigated thoroughly.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Quick Response</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Urgent matters are prioritized and addressed within 24 hours of submission.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-red-600 font-semibold tracking-wide uppercase">Report Categories</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                What Can You Report?
            </p>
        </div>

        <div class="mt-12 grid gap-8 lg:grid-cols-2 xl:grid-cols-3">
            @foreach($categories as $category)
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-3 w-3 rounded-full" style="background-color: {{ $category->color }}"></div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">{{ $category->name }}</h3>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">{{ $category->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('public.report.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                Submit Your Report Now
                <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-red-600 font-semibold tracking-wide uppercase">Simple Process</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                How It Works
            </p>
        </div>

        <div class="mt-12">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mx-auto">
                        <span class="text-xl font-bold">1</span>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Submit Report</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Fill out our secure form with details about the incident. Include any evidence you have.
                    </p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mx-auto">
                        <span class="text-xl font-bold">2</span>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Get Reference Number</h3>
                    <p class="mt-2 text-base text-gray-500">
                        You'll receive a unique reference number to track your report's progress anonymously.
                    </p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mx-auto">
                        <span class="text-xl font-bold">3</span>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Investigation & Resolution</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Our team investigates the matter and takes appropriate action to resolve the issue.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection