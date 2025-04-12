<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use App\Models\TaskDependency;
use Illuminate\Database\Eloquent\Collection;

interface TaskDependencyRepositoryInterface
{
    public function create(Task $task, Task $dependency): TaskDependency;
    public function delete(Task $task, Task $dependency): bool;
    public function getTaskDependencies(Task $task): Collection;
    public function getDependentTasks(Task $task): Collection;
    public function checkCircularDependency(Task $task, Task $dependency): bool;
}
