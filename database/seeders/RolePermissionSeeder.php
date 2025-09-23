<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Client Management
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            
            // Job Management
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',
            
            // Promoter Management
            'view promoters',
            'create promoters',
            'edit promoters',
            'delete promoters',
            
            // Promoter Position Management
            'view promoter positions',
            'create promoter positions',
            'edit promoter positions',
            'delete promoter positions',
            
            // Coordinator Management
            'view coordinators',
            'create coordinators',
            'edit coordinators',
            'delete coordinators',
            
            // Salary Sheet Management
            'view salary sheets',
            'create salary sheets',
            'edit salary sheets',
            'delete salary sheets',
            
            // Allowance Management
            'view allowances',
            'create allowances',
            'edit allowances',
            'delete allowances',
            
            // Dashboard
            'view dashboard',
            
            // Admin Panel
            'access admin panel',
            
            // Reporter Management
            'view reporters',
            'create reporters',
            'edit reporters',
            'delete reporters',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'view roles',
            'view clients',
            'create clients',
            'edit clients',
            'view jobs',
            'create jobs',
            'edit jobs',
            'view promoters',
            'create promoters',
            'edit promoters',
            'view promoter positions',
            'create promoter positions',
            'edit promoter positions',
            'view coordinators',
            'create coordinators',
            'edit coordinators',
            'view salary sheets',
            'create salary sheets',
            'edit salary sheets',
            'view allowances',
            'create allowances',
            'edit allowances',
            'view reporters',
            'create reporters',
            'edit reporters',
            'delete reporters',
            'view dashboard',
            'access admin panel',
        ]);

        $reporterRole = Role::firstOrCreate(['name' => 'reporter']);
        $reporterRole->syncPermissions([
            'view dashboard',
            'view reporters',
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view dashboard',
        ]);

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@mindpark.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );
        $adminUser->assignRole('admin');

        // Create manager user
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@mindpark.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password123'),
            ]
        );
        $managerUser->assignRole('manager');

        // Create reporter user
        $reporterUser = User::firstOrCreate(
            ['email' => 'reporter@mindpark.com'],
            [
                'name' => 'Reporter User',
                'password' => Hash::make('password123'),
                'xelenic_id' => 'REP001',
            ]
        );
        $reporterUser->assignRole('reporter');

        // Create regular user
        $regularUser = User::firstOrCreate(
            ['email' => 'user@mindpark.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password123'),
            ]
        );
        $regularUser->assignRole('user');
    }
}