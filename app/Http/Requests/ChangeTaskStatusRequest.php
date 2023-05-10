<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

/**
 * @property TaskStatusEnum $status
 */
class ChangeTaskStatusRequest extends FormRequest
{
    public TaskStatusEnum $status;

    public function rules()
    {
        return [
            'status' => ['required', new EnumRule(TaskStatusEnum::class)],
        ];
    }

    public function passedValidation()
    {
        $this->status = TaskStatusEnum::tryFrom($this->input('status'));
    }
}
