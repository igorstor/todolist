<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title'        => fake()->sentence(3),
            'description'  => fake()->sentence(12),
            'priority'     => rand(0, 5),
//            'status'       => TaskStatusEnum::todo(),
            'completed_at' => null,
            'updated_at'   => Carbon::now()->subMinutes($randomTimestampt = rand(0, 200)),
            'created_at'   => Carbon::now()->subMinutes($randomTimestampt),
        ];
    }

    public function done(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status'       => TaskStatusEnum::done(),
                'completed_at' => Carbon::now()->subMinutes(rand(0, 200)),
            ];
        });
    }

    public function highPriority(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => rand(4, 5),
            ];
        });
    }

    public function lowPriority(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => rand(1, 2),
            ];
        });
    }

    public function mediumPriority(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => 3,
            ];
        });
    }


    public function configure(): static
    {
        return $this->afterMaking(function (Task $task) {
            // ...
        })->afterCreating(function (Task $task) {
            $this->count(rand(1,3))
                 ->create(['parent_id' => $task->id]);
        });
    }
}
