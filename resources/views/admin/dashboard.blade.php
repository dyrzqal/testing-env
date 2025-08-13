<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }} - {{ ucfirst($user->role) }}
            </h2>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                      style="background-color: {{ $user->role_color }}20; color: {{ $user->role_color }};">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="text-sm text-gray-500">
                    {{ $user->department ?? 'No Department' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($user->role === 'admin')
                <!-- Admin Dashboard -->
                @include('admin.partials.admin-dashboard')
            @elseif($user->role === 'moderator')
                <!-- Moderator Dashboard -->
                @include('admin.partials.moderator-dashboard')
            @elseif($user->role === 'investigator')
                <!-- Investigator Dashboard -->
                @include('admin.partials.investigator-dashboard')
            @endif

        </div>
    </div>
</x-app-layout>