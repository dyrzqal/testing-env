<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canManageReports();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:20', 'max:5000'],
            'incident_location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'incident_date' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'incident_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'persons_involved' => ['sometimes', 'nullable', 'array', 'max:10'],
            'persons_involved.*' => ['string', 'max:255'],
            'evidence_description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'urgency_level' => ['sometimes', 'required', Rule::in(['low', 'medium', 'high', 'critical'])],
            'status' => ['sometimes', 'required', Rule::in(['submitted', 'under_review', 'investigating', 'requires_more_info', 'resolved', 'dismissed'])],
            'resolution_details' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'assigned_to_user_id' => ['sometimes', 'nullable', 'exists:users,id'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category is invalid.',
            'title.required' => 'Report title is required.',
            'description.required' => 'Report description is required.',
            'description.min' => 'Report description must be at least 20 characters.',
            'description.max' => 'Report description cannot exceed 5000 characters.',
            'incident_date.before_or_equal' => 'Incident date cannot be in the future.',
            'incident_time.date_format' => 'Incident time must be in HH:MM format.',
            'urgency_level.in' => 'Invalid urgency level selected.',
            'status.in' => 'Invalid status selected.',
            'assigned_to_user_id.exists' => 'The selected user is invalid.',
            'resolution_details.max' => 'Resolution details cannot exceed 2000 characters.'
        ];
    }
}