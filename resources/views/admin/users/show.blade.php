<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Email') }}</h3>
                        <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Role') }}</h3>
                        <p class="mt-1 text-gray-900">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Department') }}</h3>
                        <p class="mt-1 text-gray-900">{{ $user->department ?? __('None') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                </div>
            </div>

            @if($user->assignedReports->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Assigned Reports') }}</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach($user->assignedReports as $report)
                                <li class="py-2">{{ $report->title }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if($user->reportComments->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Comments') }}</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach($user->reportComments as $comment)
                                <li class="py-2">{{ $comment->body }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
