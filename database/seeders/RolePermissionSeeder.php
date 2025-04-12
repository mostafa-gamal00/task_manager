<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Task Management
            'view-all-tasks',
            'create-tasks',
            'edit-all-tasks',
            'delete-tasks',
            'manage-task-dependencies',

            // Role Management
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view-users',
            'create-users',
            'edit-users',
            'view-all-tasks',
            'create-tasks',
            'edit-all-tasks',
            'delete-tasks',
            'manage-task-dependencies',
        ]);

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view-all-tasks',
            'create-tasks',
            'edit-all-tasks',
            'manage-task-dependencies',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'create-tasks',
        ]);
    }
} 