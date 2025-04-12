<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Has full access to all features',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Can manage users and tasks',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Can manage tasks and view reports',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Can manage their own tasks',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
