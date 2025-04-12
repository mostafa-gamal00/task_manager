<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
    });

    // Task management routes
    Route::prefix('tasks')->group(function () {
        // Routes for all authenticated users
        Route::post('/', [TaskController::class, 'store'])
            // ->middleware('permission:create-tasks')
            ->name('tasks.store');

        // Routes requiring view-all-tasks permission
        Route::get('/', [TaskController::class, 'index'])
            // ->middleware('permission:view-all-tasks')
            ->name('tasks.index');
        Route::get('/filter', [TaskController::class, 'filter'])
            ->middleware('permission:view-all-tasks')
            ->name('tasks.filter');

        // Routes requiring specific task permissions
        Route::get('/{task}', [TaskController::class, 'show'])
            // ->middleware('permission:view-all-tasks')
            ->name('tasks.show');
        Route::patch('/{task}', [TaskController::class, 'update'])
            // ->middleware('permission:edit-all-tasks')
            ->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])
            ->middleware('permission:delete-tasks')
            ->name('tasks.destroy');
        
        // Task dependency routes
            Route::post('/{task}/dependencies', [TaskController::class, 'addDependency'])
                ->name('tasks.dependencies.add');
            Route::delete('/{task}/dependencies', [TaskController::class, 'removeDependency'])
                ->name('tasks.dependencies.remove');
            Route::get('/{task}/dependencies', [TaskController::class, 'getDependencies'])
                ->name('tasks.dependencies.get');
        });

    // User management routes (Admin and Super Admin only)
    Route::middleware('role:admin|super-admin')->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:view-users')
            ->name('users.index');
        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:create-users')
            ->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])
            ->middleware('permission:view-users')
            ->name('users.show');
        Route::put('/{user}', [UserController::class, 'update'])
            ->middleware('permission:edit-users')
            ->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:delete-users')
            ->name('users.destroy');
    });
});
