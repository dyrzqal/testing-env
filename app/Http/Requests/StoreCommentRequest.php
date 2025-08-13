<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
            'is_internal' => ['boolean']
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'comment.required' => 'Comment text is required.',
            'comment.min' => 'Comment must be at least 5 characters.',
            'comment.max' => 'Comment cannot exceed 2000 characters.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_internal')) {
            $this->merge([
                'is_internal' => filter_var($this->is_internal, FILTER_VALIDATE_BOOLEAN)
            ]);
        } else {
            $this->merge(['is_internal' => false]);
        }

        // Sanitize comment
        if ($this->has('comment')) {
            $this->merge([
                'comment' => trim(strip_tags($this->comment))
            ]);
        }
    }
}