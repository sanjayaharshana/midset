<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user with roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating admin user...');

        // Check if admin user already exists
        $existingUser = User::where('email', 'admin@mindpark.com')->first();
        if ($existingUser) {
            $this->info('Admin user already exists. Updating password...');
            $existingUser->update(['password' => Hash::make('password123')]);
            $existingUser->assignRole('admin');
            $this->info('Admin user password updated successfully!');
            return;
        }

        // Create admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mindpark.com',
            'password' => Hash::make('password123'),
        ]);

        // Ensure admin role exists
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Assign admin role
        $user->assignRole('admin');

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@mindpark.com');
        $this->info('Password: password123');
    }
}