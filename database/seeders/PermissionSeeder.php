<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // create permissions
        Permission::create(['name' => 'manage manager']);
        Permission::create(['name' => 'make subscribe']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view']);


        // create roles and assign existing permissions
        $role0 = Role::create(['name' => 'root']);
        $role0->givePermissionTo('manage manager');
        $role0->givePermissionTo('make subscribe');
        $role0->givePermissionTo('manage users');
        $role0->givePermissionTo('view');

        $role1 = Role::create(['name' => 'manager']);
        $role1->givePermissionTo('make subscribe');
        $role1->givePermissionTo('manage users');
        $role1->givePermissionTo('view');

        $role2 = Role::create(['name' => 'subscriber']);
        $role2->givePermissionTo('make subscribe');
        $role2->givePermissionTo('view');

        $role3 = Role::create(['name' => 'viewer']);
        $role3->givePermissionTo('view');
        
        // create root user
        // root, 00000000
        $user = \App\Models\User::factory()->create([
            'name' => 'root',
            'email' => 'root@root',
            'password' => '$2y$10$HvVQrNLcW9gQAo5F.gCyYO4MOLq.1fs/1bq7b17.LwcTc5Qyahfr6',
        ]);
        $user->assignRole($role0);

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example Manager',
            'email' => 'm@example.com',
            'password' => '$2y$10$HvVQrNLcW9gQAo5F.gCyYO4MOLq.1fs/1bq7b17.LwcTc5Qyahfr6',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Subscriber',
            'email' => 's@example.com',
            'password' => '$2y$10$HvVQrNLcW9gQAo5F.gCyYO4MOLq.1fs/1bq7b17.LwcTc5Qyahfr6',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'u@example.com',
            'password' => '$2y$10$HvVQrNLcW9gQAo5F.gCyYO4MOLq.1fs/1bq7b17.LwcTc5Qyahfr6',
        ]);
        $user->assignRole($role3);
    }
}
