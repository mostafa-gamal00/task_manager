<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            [
                'name' => 'View Users',
                'slug' => 'view-users',
                'description' => 'Can view all users',
            ],
            [
                'name' => 'Create Users',
                'slug' => 'create-users',
                'description' => 'Can create new users',
            ],
            [
                'name' => 'Edit Users',
                'slug' => 'edit-users',
                'description' => 'Can edit existing users',
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'delete-users',
                'description' => 'Can delete users',
            ],

            // Task Management
            [
                'name' => 'View All Tasks',
                'slug' => 'view-all-tasks',
                'description' => 'Can view all tasks',
            ],
            [
                'name' => 'Create Tasks',
                'slug' => 'create-tasks',
                'description' => 'Can create new tasks',
            ],
            [
                'name' => 'Edit All Tasks',
                'slug' => 'edit-all-tasks',
                'description' => 'Can edit any task',
            ],
            [
                'name' => 'Delete Tasks',
                'slug' => 'delete-tasks',
                'description' => 'Can delete tasks',
            ],
            [
                'name' => 'Manage Task Dependencies',
                'slug' => 'manage-task-dependencies',
                'description' => 'Can manage task dependencies',
            ],

            // Role Management
            [
                'name' => 'Manage Roles',
                'slug' => 'manage-roles',
                'description' => 'Can manage roles and permissions',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 