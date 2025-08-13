<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canManageUsers();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\'\.]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'role' => ['required', Rule::in(['admin', 'moderator', 'investigator'])],
            'department' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean']
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.regex' => 'Name contains invalid characters.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'User role is required.',
            'role.in' => 'Invalid user role selected.'
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
        } else {
            $this->merge(['is_active' => true]);
        }

        // Sanitize name
        if ($this->has('name')) {
            $this->merge([
                'name' => trim(strip_tags($this->name))
            ]);
        }
    }
}