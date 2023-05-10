<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Models\Builders\TaskBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'completed_at',
    ];

    protected $casts = [
        'status'       => TaskStatusEnum::class,
        'completed_at' => 'datetime',
    ];

    public static $orderFields = [
        'priority',
        'created_at',
        'completed_at',
    ];

    public function newEloquentBuilder($query)
    {
        return new TaskBuilder($query);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }

    public function markAsDone(): self
    {
        $this->status       = TaskStatusEnum::done();
        $this->completed_at = Carbon::now();
        $this->save();

        return $this;
    }

    public function markAsToDo(): self
    {
        $this->status       = TaskStatusEnum::todo();
        $this->completed_at = null;
        $this->save();

        return $this;
    }

    public function checkIfIsDone(): bool
    {
        return $this->status->equals(TaskStatusEnum::done());
    }

    public function checkIfHasSubTasksToDo(): bool
    {
        return $this->subtasks()->todo()->exists();
    }
}
