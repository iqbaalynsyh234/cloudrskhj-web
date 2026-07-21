<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'file-list',
            'file-upload',
            'file-download',
            'file-delete',
            'file-delete-any',  // can delete other users' files
            'user-list',
            'user-manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Admin role with all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create User role with limited permissions
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'file-list',
            'file-upload',
            'file-download',
            'file-delete',
        ]);

        // Create default Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@cloud.local'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create sample users
        $sampleUsers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@cloud.local'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@cloud.local'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@cloud.local'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@cloud.local'],
            ['name' => 'Rizky Pratama', 'email' => 'rizky@cloud.local'],
        ];

        foreach ($sampleUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                ]
            );
            $user->assignRole('user');
        }
    }
}
