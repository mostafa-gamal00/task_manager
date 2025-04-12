<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskDependency;
use App\Repositories\Interfaces\TaskDependencyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskDependencyRepository implements TaskDependencyRepositoryInterface
{
    public function create(Task $task, Task $dependency): TaskDependency
    {
        return TaskDependency::create([
            'task_id' => $task->id,
            'dependency_id' => $dependency->id
        ]);
    }

    public function delete(Task $task, Task $dependency): bool
    {
        return TaskDependency::where('task_id', $task->id)
            ->where('dependency_id', $dependency->id)
            ->delete();
    }

    public function getTaskDependencies(Task $task): Collection
    {
        return $task->dependencies;
    }

    public function getDependentTasks(Task $task): Collection
    {
        return $task->dependents;
    }

    public function checkCircularDependency(Task $task, Task $dependency): bool
    {
        $visited = [];
        return $this->hasCycle($dependency, $task, $visited);
    }

    private function hasCycle(Task $current, Task $target, array &$visited): bool
    {
        if ($current->id === $target->id) {
            return true;
        }

        if (in_array($current->id, $visited)) {
            return false;
        }

        $visited[] = $current->id;

        foreach ($current->dependencies as $dependency) {
            if ($this->hasCycle($dependency, $target, $visited)) {
                return true;
            }
        }

        return false;
    }
}
