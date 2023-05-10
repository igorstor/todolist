<?php

namespace App\Policies;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function delete(?User $user, Task $task)
    {
        if ($task->checkIfIsDone()) {
            return $this->deny('this task is done. You can\'t delete it.');
        }

        return true;
    }

    public function changeStatus(?User $user, Task $task, TaskStatusEnum $status)
    {
        if ($status->equals(TaskStatusEnum::done()) && $task->checkIfHasSubTasksToDo()) {
            return $this->deny('This task has not completed subtask. Please compete all remaining subtasks and try again.');
        }

        return true;
    }
}
