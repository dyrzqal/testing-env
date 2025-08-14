<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Report') }}
            </h2>
            <a href="{{ route('admin.reports.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Reports') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.reports.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Report Title *</label>
                                    <input type="text" name="title" id="title" 
                                           value="{{ old('title') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                           required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                    <select name="category_id" id="category_id" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                            required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                                <textarea name="description" id="description" rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                          placeholder="Provide a detailed description of the incident..."
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Incident Details -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Incident Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="incident_location" class="block text-sm font-medium text-gray-700">Incident Location *</label>
                                    <input type="text" name="incident_location" id="incident_location" 
                                           value="{{ old('incident_location') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('incident_location') border-red-500 @enderror"
                                           placeholder="e.g., Building A, Floor 3, Room 301"
                                           required>
                                    @error('incident_location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="incident_date" class="block text-sm font-medium text-gray-700">Incident Date *</label>
                                    <input type="date" name="incident_date" id="incident_date" 
                                           value="{{ old('incident_date') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('incident_date') border-red-500 @enderror"
                                           required>
                                    @error('incident_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="incident_time" class="block text-sm font-medium text-gray-700">Incident Time *</label>
                                    <input type="time" name="incident_time" id="incident_time" 
                                           value="{{ old('incident_time') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('incident_time') border-red-500 @enderror"
                                           required>
                                    @error('incident_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="urgency_level" class="block text-sm font-medium text-gray-700">Urgency Level *</label>
                                    <select name="urgency_level" id="urgency_level" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('urgency_level') border-red-500 @enderror"
                                            required>
                                        <option value="">Select Urgency</option>
                                        <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="critical" {{ old('urgency_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                    @error('urgency_level')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assignment -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment</h3>
                            
                            <div>
                                <label for="assigned_to_user_id" class="block text-sm font-medium text-gray-700">Assign To Investigator</label>
                                <select name="assigned_to_user_id" id="assigned_to_user_id" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('assigned_to_user_id') border-red-500 @enderror">
                                    <option value="">Unassigned</option>
                                    @foreach($investigators as $investigator)
                                        <option value="{{ $investigator->id }}" {{ old('assigned_to_user_id') == $investigator->id ? 'selected' : '' }}>
                                            {{ $investigator->name }} ({{ $investigator->department ?? 'No Department' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to_user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Leave unassigned to review later</p>
                            </div>
                        </div>
                        
                        <!-- Attachments -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attachments</h3>
                            
                            <div>
                                <label for="attachments" class="block text-sm font-medium text-gray-700">Upload Files</label>
                                <input type="file" name="attachments[]" id="attachments" multiple
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('attachments.*') border-red-500 @enderror"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('attachments.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB each)</p>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.reports.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Create Report') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>