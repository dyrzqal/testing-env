<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anonymous reporting is allowed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-_.,!?()\[\]{}]+$/'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'incident_location' => ['nullable', 'string', 'max:255'],
            'incident_date' => ['nullable', 'date', 'before_or_equal:today'],
            'incident_time' => ['nullable', 'date_format:H:i'],
            'persons_involved' => ['nullable', 'array', 'max:10'],
            'persons_involved.*' => ['string', 'max:255'],
            'evidence_description' => ['nullable', 'string', 'max:1000'],
            'urgency_level' => ['required', Rule::in(['low', 'medium', 'high', 'critical'])],
            'is_anonymous' => ['boolean'],
            'reporter_name' => ['required_if:is_anonymous,false', 'nullable', 'string', 'max:255'],
            'reporter_email' => ['required_if:is_anonymous,false', 'nullable', 'email', 'max:255'],
            'reporter_phone' => ['nullable', 'string', 'regex:/^[+]?[0-9\s\-()]+$/', 'max:20'],
            'reporter_department' => ['nullable', 'string', 'max:255'],
            'reporter_contact_preference' => ['nullable', 'string', 'max:500'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,mp4,avi,mov,zip,rar']
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a report category.',
            'category_id.exists' => 'The selected category is invalid.',
            'title.required' => 'Report title is required.',
            'title.regex' => 'Report title contains invalid characters.',
            'description.required' => 'Report description is required.',
            'description.min' => 'Report description must be at least 20 characters.',
            'description.max' => 'Report description cannot exceed 5000 characters.',
            'incident_date.before_or_equal' => 'Incident date cannot be in the future.',
            'incident_time.date_format' => 'Incident time must be in HH:MM format.',
            'persons_involved.max' => 'Cannot add more than 10 persons involved.',
            'persons_involved.*.max' => 'Person name cannot exceed 255 characters.',
            'urgency_level.required' => 'Please select an urgency level.',
            'urgency_level.in' => 'Invalid urgency level selected.',
            'reporter_name.required_if' => 'Name is required for non-anonymous reports.',
            'reporter_email.required_if' => 'Email is required for non-anonymous reports.',
            'reporter_email.email' => 'Please provide a valid email address.',
            'reporter_phone.regex' => 'Please provide a valid phone number.',
            'attachments.max' => 'Cannot upload more than 10 files.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each file cannot exceed 10MB.',
            'attachments.*.mimes' => 'File type not allowed. Allowed types: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF, MP4, AVI, MOV, ZIP, RAR.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_anonymous')) {
            $this->merge([
                'is_anonymous' => filter_var($this->is_anonymous, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Sanitize text inputs
        if ($this->has('title')) {
            $this->merge([
                'title' => strip_tags($this->title)
            ]);
        }

        if ($this->has('description')) {
            $this->merge([
                'description' => strip_tags($this->description)
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'reporter_name' => 'name',
            'reporter_email' => 'email',
            'reporter_phone' => 'phone number',
            'reporter_department' => 'department',
            'incident_date' => 'incident date',
            'incident_time' => 'incident time',
            'incident_location' => 'incident location',
            'urgency_level' => 'urgency level',
            'evidence_description' => 'evidence description'
        ];
    }
}