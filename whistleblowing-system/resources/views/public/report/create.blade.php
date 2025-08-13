@extends('layouts.public')

@section('title', 'Submit Anonymous Report')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Submit Anonymous Report
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                All information submitted is confidential and handled securely. Your identity will remain anonymous.
            </p>
        </div>

        <!-- Security Notice -->
        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Your report is secure and anonymous
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>We use industry-standard encryption to protect your submission. You can track your report using the reference number provided after submission.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-lg rounded-lg">
            <form action="{{ route('public.report.store') }}" method="POST" enctype="multipart/form-data" x-data="reportForm()">
                @csrf
                
                <div class="px-6 py-8 space-y-8">
                    <!-- Category Selection -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">What type of misconduct are you reporting?</p>
                        <select id="category_id" name="category_id" required class="mt-2 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md @error('category_id') border-red-300 @enderror">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Report Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Report Title <span class="text-red-500">*</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Provide a brief, descriptive title for your report</p>
                        <input type="text" name="title" id="title" required maxlength="255" value="{{ old('title') }}" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('title') border-red-300 @enderror" placeholder="Brief description of the incident">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Detailed Description <span class="text-red-500">*</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Provide as much detail as possible about the incident (minimum 50 characters)</p>
                        <textarea name="description" id="description" rows="6" required minlength="50" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('description') border-red-300 @enderror" placeholder="Describe what happened, when it occurred, who was involved, and any other relevant details...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Incident Details -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="incident_location" class="block text-sm font-medium text-gray-700">
                                Location of Incident
                            </label>
                            <input type="text" name="incident_location" id="incident_location" value="{{ old('incident_location') }}" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('incident_location') border-red-300 @enderror" placeholder="Building, department, or general location">
                            @error('incident_location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="urgency_level" class="block text-sm font-medium text-gray-700">
                                Urgency Level <span class="text-red-500">*</span>
                            </label>
                            <select name="urgency_level" id="urgency_level" required class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('urgency_level') border-red-300 @enderror">
                                <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('urgency_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('urgency_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('urgency_level')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="incident_date" class="block text-sm font-medium text-gray-700">
                                Date of Incident
                            </label>
                            <input type="date" name="incident_date" id="incident_date" value="{{ old('incident_date') }}" max="{{ date('Y-m-d') }}" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('incident_date') border-red-300 @enderror">
                            @error('incident_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="incident_time" class="block text-sm font-medium text-gray-700">
                                Time of Incident
                            </label>
                            <input type="time" name="incident_time" id="incident_time" value="{{ old('incident_time') }}" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('incident_time') border-red-300 @enderror">
                            @error('incident_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Persons Involved -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Persons Involved
                        </label>
                        <p class="mt-1 text-sm text-gray-500">List names or descriptions of people involved in the incident</p>
                        <div x-data="{ persons: [''] }" class="mt-2 space-y-2">
                            <template x-for="(person, index) in persons" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'persons_involved[' + index + ']'" x-model="persons[index]" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Name or description of person involved">
                                    <button type="button" @click="persons.splice(index, 1)" x-show="persons.length > 1" class="text-red-600 hover:text-red-800">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="persons.push('')" class="text-sm text-red-600 hover:text-red-800">
                                + Add another person
                            </button>
                        </div>
                    </div>

                    <!-- Evidence Description -->
                    <div>
                        <label for="evidence_description" class="block text-sm font-medium text-gray-700">
                            Evidence Description
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Describe any evidence you have or know about</p>
                        <textarea name="evidence_description" id="evidence_description" rows="3" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('evidence_description') border-red-300 @enderror" placeholder="Documents, emails, recordings, witnesses, etc.">{{ old('evidence_description') }}</textarea>
                        @error('evidence_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Attachments -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Upload Evidence Files
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Upload documents, images, or other evidence (Max 10MB per file)</p>
                        <div class="mt-2">
                            <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mp3,.wav" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                        @error('attachments.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Anonymity Options -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_anonymous" name="is_anonymous" type="checkbox" checked x-model="isAnonymous" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_anonymous" class="font-medium text-gray-700">Submit this report anonymously</label>
                                    <p class="text-gray-500">Your identity will not be recorded or shared. This is recommended for sensitive reports.</p>
                                </div>
                            </div>

                            <!-- Contact Information (shown only if not anonymous) -->
                            <div x-show="!isAnonymous" x-transition class="space-y-6 bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900">Contact Information (Optional)</h4>
                                <p class="text-sm text-gray-600">Providing contact information allows us to follow up if we need additional details.</p>
                                
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="reporter_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="reporter_name" id="reporter_name" value="{{ old('reporter_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reporter_name') border-red-300 @enderror">
                                        @error('reporter_name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="reporter_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" name="reporter_email" id="reporter_email" value="{{ old('reporter_email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reporter_email') border-red-300 @enderror">
                                        @error('reporter_email')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="reporter_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" name="reporter_phone" id="reporter_phone" value="{{ old('reporter_phone') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reporter_phone') border-red-300 @enderror">
                                        @error('reporter_phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="reporter_department" class="block text-sm font-medium text-gray-700">Department/Organization</label>
                                        <input type="text" name="reporter_department" id="reporter_department" value="{{ old('reporter_department') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reporter_department') border-red-300 @enderror">
                                        @error('reporter_department')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="reporter_contact_preference" class="block text-sm font-medium text-gray-700">Preferred Contact Method</label>
                                    <textarea name="reporter_contact_preference" id="reporter_contact_preference" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reporter_contact_preference') border-red-300 @enderror" placeholder="How would you prefer to be contacted? Any specific times or methods?">{{ old('reporter_contact_preference') }}</textarea>
                                    @error('reporter_contact_preference')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('public.report.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-500">
                            ← Back to Home
                        </a>
                        <div class="flex space-x-3">
                            <button type="button" onclick="history.back()" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Submit Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reportForm() {
    return {
        isAnonymous: true
    }
}
</script>
@endpush
@endsection