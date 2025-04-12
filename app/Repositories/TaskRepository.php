<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection
    {
        return Task::with(['dependencies'])->get();
    }

    public function find(int $id): ?Task
    {
        return Task::with(['dependencies'])->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): bool
    {
        $task->delete();
        return true;
    }

    public function getByAssignee(int $userId): Collection
    {
        return Task::where('assignee_id', $userId)->get();
    }

    public function getByStatus(string $status): Collection
    {
        return Task::where('status', $status)->get();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return Task::whereBetween('due_date', [$startDate, $endDate])->get();
    }

    public function addDependency(Task $task, Task $dependency): bool
    {
        DB::transaction(function () use ($task, $dependency) {
            if ($this->hasCycle($task, $dependency)) {
                throw new \Exception('Circular dependency detected');
            }
            $task->dependencies()->attach($dependency->id);
        });
        return true;
    }

    public function removeDependency(Task $task, Task $dependency): bool
    {
        $task->dependencies()->detach($dependency->id);
        return true;
    }

    public function getDependencies(Task $task): Collection
    {
        return $task->dependencies;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Task::paginate($perPage);
    }

    public function filter(array $filters): Collection
    {
        $query = Task::with(['dependencies', 'dependents']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('due_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('due_date', '<=', $filters['end_date']);
        }

        if (isset($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        return $query->get();
    }

    private function hasCycle(Task $task, Task $dependency): bool
    {
        $visited = [];
        $stack = [$dependency->id];

        while (!empty($stack)) {
            $current = array_pop($stack);
            
            if ($current === $task->id) {
                return true;
            }

            if (!isset($visited[$current])) {
                $visited[$current] = true;
                $dependencies = Task::find($current)->dependencies;
                
                foreach ($dependencies as $dep) {
                    $stack[] = $dep->id;
                }
            }
        }

        return false;
    }
}
