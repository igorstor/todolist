<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class TaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parent_id'   => ['sometimes', 'integer', new Exists(Task::class, 'parent_id')],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:65535'],
            'priority'    => ['sometimes', 'integer', 'min:1', 'max:5'],
        ];
    }
}
