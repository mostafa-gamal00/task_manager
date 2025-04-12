<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'assignee_id' => ['required', 'exists:users,id'],
        ];

        if ($this->isMethod('PATCH')) {
            $rules = [
                'title' => ['sometimes', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'due_date' => ['sometimes', 'date', 'after_or_equal:today'],
                'assignee_id' => ['sometimes', 'exists:users,id'],
                'status' => ['sometimes', Rule::in(['pending', 'in_progress', 'completed', 'canceled'])],
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'title.max' => 'The task title may not be greater than 255 characters.',
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
            'assignee_id.required' => 'The assignee is required.',
            'assignee_id.exists' => 'The selected assignee is invalid.',
            'status.in' => 'The status must be one of: pending, in_progress, completed, or canceled.',
        ];
    }
} 