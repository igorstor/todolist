<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory()
            ->count(3)
            ->done()
            ->highPriority()
            ->create();

        Task::factory()
            ->count(3)
            ->done()
            ->lowPriority()
            ->create();

        Task::factory()
            ->count(3)
            ->lowPriority()
            ->create();

        Task::factory()
            ->count(3)
            ->highPriority()
            ->create();

        Task::factory()
            ->count(3)
            ->mediumPriority()
            ->create();
    }
}
