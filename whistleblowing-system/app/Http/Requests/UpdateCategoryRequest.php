<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canManageCategories();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')->id ?? $this->route('category');
        
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($categoryId)],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:999']
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'A category with this name already exists.',
            'name.max' => 'Category name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order cannot be negative.',
            'sort_order.max' => 'Sort order cannot exceed 999.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}