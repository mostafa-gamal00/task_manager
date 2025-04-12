<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskDependencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dependency_id' => [
                'required',
                'exists:tasks,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->route('task')->id) {
                        $fail('A task cannot depend on itself.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dependency_id.required' => 'The dependency ID is required.',
            'dependency_id.exists' => 'The selected dependency task does not exist.',
        ];
    }
} 