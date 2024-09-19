<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'priority' => ['required', 'max:50'],
            'status' => ['required', 'max:50'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
