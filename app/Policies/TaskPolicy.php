<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        // Users can view tasks assigned to them
        return $user->hasRole('manager') || $task->assignee_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Only managers can create tasks
        return $user->hasRole('manager');
    }

    public function update(User $user, Task $task): bool
    {
        // Managers can update any task
        if ($user->hasRole('manager')) {
            return true;
        }

        // Regular users can only update status of tasks assigned to them
        return $task->assignee_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        // Only managers can delete tasks
        return $user->hasRole('manager');
    }

    public function assign(User $user): bool
    {
        // Only managers can assign tasks
        return $user->hasRole('manager');
    }
} 