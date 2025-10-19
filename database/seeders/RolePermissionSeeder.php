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
            
            // Brand Management
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            
            // Job Management
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',
            'manage job settings',
            'manage job allowance rules',
            
            // Promoter Management
            'view promoters',
            'create promoters',
            'edit promoters',
            'delete promoters',
            'import promoters',
            'export promoters',
            'print promoter salary slips',
            
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
            'export coordinators',
            'import coordinators',
            
            // Salary Sheet Management
            'view salary sheets',
            'create salary sheets',
            'edit salary sheets',
            'delete salary sheets',
            'print salary sheets',
            'export salary sheets',
            'import salary sheets',
            'enforce salary sheets',
            
            // Position Wise Salary Rules Management
            'view position wise salary rules',
            'create position wise salary rules',
            'edit position wise salary rules',
            'delete position wise salary rules',
            'bulk create position wise salary rules',
            
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
            
            // Officer Management
            'view officers',
            'create officers',
            'edit officers',
            'delete officers',
            
            // Settings Management
            'view settings',
            'edit settings',
            'create settings',
            'delete settings',
            'export settings',
            'import settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            // User Management
            'view users',
            'create users',
            'edit users',
            
            // Role Management
            'view roles',
            
            // Client Management
            'view clients',
            'create clients',
            'edit clients',
            
            // Job Management
            'view jobs',
            'create jobs',
            'edit jobs',
            'manage job settings',
            'manage job allowance rules',
            
            // Promoter Management
            'view promoters',
            'create promoters',
            'edit promoters',
            'import promoters',
            'export promoters',
            'print promoter salary slips',
            
            // Promoter Position Management
            'view promoter positions',
            'create promoter positions',
            'edit promoter positions',
            
            // Coordinator Management
            'view coordinators',
            'create coordinators',
            'edit coordinators',
            'export coordinators',
            'import coordinators',
            
            // Salary Sheet Management
            'view salary sheets',
            'create salary sheets',
            'edit salary sheets',
            'print salary sheets',
            'export salary sheets',
            'enforce salary sheets',
            
            // Position Wise Salary Rules Management
            'view position wise salary rules',
            'create position wise salary rules',
            'edit position wise salary rules',
            'bulk create position wise salary rules',
            
            // Allowance Management
            'view allowances',
            'create allowances',
            'edit allowances',
            
            // Reporter Management
            'view reporters',
            'create reporters',
            'edit reporters',
            'delete reporters',
            
            // Officer Management
            'view officers',
            'create officers',
            'edit officers',
            'delete officers',
            
            // Settings Management
            'view settings',
            'edit settings',
            'create settings',
            'export settings',
            'import settings',
            
            // Dashboard & Admin Panel
            'view dashboard',
            'access admin panel',
        ]);

        $reporterRole = Role::firstOrCreate(['name' => 'reporter']);
        $reporterRole->syncPermissions([
            'view dashboard',
            'view reporters',
            'view promoters',
            'view salary sheets',
            'print salary sheets',
            'print promoter salary slips',
        ]);

        $officerRole = Role::firstOrCreate(['name' => 'officer']);
        $officerRole->syncPermissions([
            'view dashboard',
            'view officers',
            'view promoters',
            'view coordinators',
            'view salary sheets',
            'print salary sheets',
        ]);

        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $hrRole->syncPermissions([
            'view dashboard',
            'view users',
            'view promoters',
            'view coordinators',
            'view reporters',
            'view officers',
            'view salary sheets',
            'print salary sheets',
            'print promoter salary slips',
            'view position wise salary rules',
            'view allowances',
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

        // Create officer user
        $officerUser = User::firstOrCreate(
            ['email' => 'officer@mindpark.com'],
            [
                'name' => 'Officer User',
                'password' => Hash::make('password123'),
                'xelenic_id' => 'OFF001',
            ]
        );
        $officerUser->assignRole('officer');

        // Create HR user
        $hrUser = User::firstOrCreate(
            ['email' => 'hr@mindpark.com'],
            [
                'name' => 'HR User',
                'password' => Hash::make('password123'),
            ]
        );
        $hrUser->assignRole('hr');

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