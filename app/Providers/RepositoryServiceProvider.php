<?php

namespace App\Providers;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\TaskDependencyRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Repositories\TaskDependencyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );

        $this->app->bind(
            TaskDependencyRepositoryInterface::class,
            TaskDependencyRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
} 