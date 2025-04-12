<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Task;
    public function create(array $data): Task;
    public function update(Task $task, array $data): Task;
    public function delete(Task $task): bool;
    public function getByAssignee(int $userId): Collection;
    public function getByStatus(string $status): Collection;
    public function getByDateRange(string $startDate, string $endDate): Collection;
    public function addDependency(Task $task, Task $dependency): bool;
    public function removeDependency(Task $task, Task $dependency): bool;
    public function getDependencies(Task $task): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function filter(array $filters): Collection;
} 