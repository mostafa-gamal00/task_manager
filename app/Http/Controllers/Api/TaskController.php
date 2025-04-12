<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskDependencyRequest;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\TaskDependencyRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskDependencyRepositoryInterface $taskDependencyRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->roles->pluck('name')->contains('manager')) {
            $tasks = $this->taskRepository->all();
            return $this->successResponse($tasks, 'Tasks retrieved successfully');
        } else {
            $tasks = $this->taskRepository->getByAssignee($user->id);
            return $this->successResponse($tasks, 'Tasks retrieved successfully');
        }
    }

    public function store(TaskRequest $request): JsonResponse
    {

        $validated = $request->validated();
        $validated['created_by_id'] = Auth::id();
        $validated['status'] = Task::STATUS_PENDING;

        $task = $this->taskRepository->create($validated);

        return $this->successResponse($task, 'Task created successfully', 201);
    }

    public function show(Task $task): JsonResponse
    {

        $task = $this->taskRepository->find($task->id);
        return $this->successResponse($task, 'Task retrieved successfully');
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {

        $validated = $request->validated();

        // If user is not a manager, they can only update the status
            if (!Auth::user()->roles->pluck('name')->contains('manager')) {
            if (count($validated) > 1 || !isset($validated['status'])) {
                return $this->errorResponse(
                    'Unauthorized',
                    'You can only update the status of tasks assigned to you',
                    403
                );
            }
        }

        // Check if status is being updated
        if (isset($validated['status']) && in_array($validated['status'], ['completed', 'in_progress'])) {
            $dependencies = $this->taskDependencyRepository->getTaskDependencies($task);
            
            foreach ($dependencies as $dependency) {
                if (!in_array($dependency->status, ['completed', 'canceled'])) {
                    return $this->errorResponse(
                        'Cannot update task status',
                        'All dependencies must be completed or canceled before updating the task status',
                        422
                    );
                }
            }
        }

        $task = $this->taskRepository->update($task, $validated);

        return $this->successResponse($task, 'Task updated successfully');
    }

    public function destroy(Task $task): JsonResponse
    {

        $this->taskRepository->delete($task);

        return $this->successResponse(null, 'Task deleted successfully', 204);
    }

    public function addDependency(TaskDependencyRequest $request, Task $task): JsonResponse
    {

        $validated = $request->validated();
        $dependency = Task::findOrFail($validated['dependency_id']);
        
        if ($this->taskDependencyRepository->checkCircularDependency($task, $dependency)) {
            return $this->errorResponse('Circular dependency detected', null, 422);
        }

        $this->taskRepository->addDependency($task, $dependency);

        return $this->successResponse(null, 'Dependency added successfully');
    }

    public function removeDependency(TaskDependencyRequest $request, Task $task): JsonResponse
    {

        $validated = $request->validated();
        $dependency = Task::findOrFail($validated['dependency_id']);
        $this->taskRepository->removeDependency($task, $dependency);

        return $this->successResponse(null, 'Dependency removed successfully');
    }

    public function getDependencies(Task $task): JsonResponse
    {

        $dependencies = $this->taskDependencyRepository->getTaskDependencies($task);
        return $this->successResponse($dependencies, 'Dependencies retrieved successfully');
    }

    public function filter(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'sometimes|in:pending,in_progress,completed,canceled',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'assignee_id' => 'sometimes|exists:users,id',
            ]);

            $user = Auth::user();
            if ($user->roles->pluck('name')->contains('manager')) {
                $validated['assignee_id'] = $user->id;
            }

            $tasks = $this->taskRepository->filter($validated);
            return $this->successResponse($tasks, 'Tasks filtered successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', $e->errors(), 422);
        }
    }
} 