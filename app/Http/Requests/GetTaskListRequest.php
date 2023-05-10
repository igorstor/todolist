<?php

namespace App\Http\Requests;

use App\Collections\OrderingInputsCollection;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\ValueObjects\PriorityValueObject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Enum\Laravel\Rules\EnumRule;

/**
 * @property string|null $search
 * @property string|null $priority_from
 * @property string|null $priority_to
 * @property array|null $statuses
 * @property array|null $sort
 * @property string|null $order_by
 * @property string|null $order_direction
 * @property PriorityValueObject|null $priority
 */
class GetTaskListRequest extends FormRequest
{
    public ?PriorityValueObject $priority = null;
    public ?OrderingInputsCollection $sortOrder = null;

    public function rules()
    {
        return [
            'search'           => ['sometimes', 'string', 'max:60'],
            'priority_from'    => ['sometimes', 'integer', 'min:1', 'max:5'],
            'priority_to'      => ['sometimes', 'integer', 'min:1', 'max:5'],
            'statuses'         => ['sometimes', 'array', new EnumRule(TaskStatusEnum::class)],
            'statuses.*'       => ['required', new EnumRule(TaskStatusEnum::class)],
            'sort'             => ['sometimes', 'array'],
            'sort.*.column'    => ['required', 'distinct', Rule::in(Task::$orderFields)],
            'sort.*.direction' => ['required', Rule::in('asc', 'desc')],
        ];
    }

    public function passedValidation()
    {
        if ($this->priority_from || $this->priority_to) {
            $this->priority = PriorityValueObject::make($this->priority_from, $this->priority_to);
        }

        $this->resolveOrderingParameters();
    }

    public function resolveOrderingParameters()
    {
        $this->sortOrder = OrderingInputsCollection::make($this->sort);
    }
}
