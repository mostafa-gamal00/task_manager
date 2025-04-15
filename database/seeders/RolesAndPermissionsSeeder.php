<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'edit tasks']);
        Permission::create(['name' => 'delete tasks']);
        Permission::create(['name' => 'manage tasks']);

        // Create roles and assign permissions
    //     $user = Role::create(['name' => 'user']);
    //     $user->givePermissionTo([
    //         'view tasks',
    //         'create tasks',
    //         'edit tasks',
    //         'delete tasks'
    //     ]);

    //     $manager = Role::create(['name' => 'manager']);
    //     $manager->givePermissionTo([
    //         'view tasks',
    //         'create tasks',
    //         'edit tasks',
    //         'delete tasks',
    //         'manage tasks'
    //     ]);
    }
} 