<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Http\Requests\GetTaskListRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{
    public function index(GetTaskListRequest $request)
    {
        $tasks = Task::query()
                     ->with('subtasks')
                     ->searchIfSet($request->search)
                     ->filterByPriorityIfSet($request->priority)
                     ->filterByStatusesIfSet($request->statuses)
                     ->orderIfSet($request->sortOrder)
                     ->simplePaginate();

        return TaskResource::collection($tasks);
    }

    public function show(Task $task)
    {
        return TaskResource::make($task);
    }

    public function store(TaskRequest $request)
    {
        $task = Task::query()
                    ->create(array_merge($request->validated(), [
                        'status' => TaskStatusEnum::todo()
                    ]));

        return TaskResource::make($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return TaskResource::make($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return Response::noContent();
    }
}
