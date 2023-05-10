<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Http\Requests\ChangeTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskStatusController extends Controller
{
    public function update(ChangeTaskStatusRequest $request, Task $task)
    {
        $this->authorize('changeStatus', [$task, $request->status]);

        if ($request->status->equals(TaskStatusEnum::done())) {
            $task = $task->markAsDone();
        } else {
            $task = $task->markAsToDo();
        }

        return TaskResource::make($task);
    }
}
