<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions with the 'sanctum' guard
        Permission::create(['name' => 'edit', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'read', 'guard_name' => 'sanctum']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'OPD Front Desk', 'guard_name' => 'sanctum']);
        $role1->givePermissionTo('edit'); // Use the correct permission name
        $role1->givePermissionTo('create');

        $role2 = Role::create(['name' => 'admin', 'guard_name' => 'sanctum']);
        $role2->givePermissionTo('create');
        $role2->givePermissionTo('read');

        $role3 = Role::create(['name' => 'Super-Admin', 'guard_name' => 'sanctum']);
        $role3->givePermissionTo('create');
        $role3->givePermissionTo('read');
        $role3->givePermissionTo('edit');
        $role3->givePermissionTo('delete');
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'user@aims.com',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Admin User',
            'email' => 'admin@aims.com',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Super-Admin User',
            'email' => 'kh.marchal@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole($role3);
    }
}
