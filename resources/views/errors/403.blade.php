<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-center">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Access Denied</h1>
                <p class="text-gray-600 mb-6">You don't have permission to access this resource.</p>
                
                <div class="space-y-3">
                    <a href="{{ url()->previous() }}" class="block w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                        Go Back
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="block w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Go to Dashboard
                    </a>
                </div>
                
                @if(auth()->check())
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <strong>Current Role:</strong> {{ ucfirst(auth()->user()->role) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Department:</strong> {{ auth()->user()->department ?? 'Not assigned' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>