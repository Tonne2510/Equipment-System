<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles and Permissions first
        $this->call(RolePermissionSeeder::class);

        // Get created roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'phone' => '0123456789',
            'status' => 1,
        ]);

        // Create Manager User
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role_id' => $managerRole->id,
            'phone' => '0123456790',
            'status' => 1,
        ]);

        // Create Employee Users
        User::factory(5)->create([
            'role_id' => $employeeRole->id,
            'status' => 1,
        ]);

        // Seed equipment data
        $this->call(EquipmentSeeder::class);

        // Seed borrow requests and items
        $this->call(BorrowSeeder::class);

        // Seed maintenance records
        $this->call(MaintenanceSeeder::class);

        // Seed incident reports
        $this->call(IncidentSeeder::class);

        // Seed violations and penalties
        $this->call(ViolationSeeder::class);
    }
}

