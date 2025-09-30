<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['username' => 'admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('admin'), 'role' => 'superadmin', 'job_title' => 'Administrator', 'position' => 'IT', 'email_verified_at' => now()],
            ['username' => 'manager1', 'email' => 'manager1@company.com', 'password' => Hash::make('user'), 'role' => 'viewer', 'job_title' => 'Manager', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'staff1', 'email' => 'staff1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Staff', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'staff2', 'email' => 'staff2@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Staff', 'position' => 'Warehouse', 'email_verified_at' => now()],
            ['username' => 'supervisor1', 'email' => 'supervisor1@company.com', 'password' => Hash::make('user'), 'role' => 'viewer', 'job_title' => 'Supervisor', 'position' => 'Warehouse', 'email_verified_at' => now()],
            ['username' => 'cashier1', 'email' => 'cashier1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Cashier', 'position' => 'Cashier', 'email_verified_at' => now()],
            ['username' => 'cashier2', 'email' => 'cashier2@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Cashier', 'position' => 'Cashier', 'email_verified_at' => now()],
            ['username' => 'purchaser1', 'email' => 'purchaser1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Staff', 'position' => 'Purchasing', 'email_verified_at' => now()],
            ['username' => 'accounting1', 'email' => 'accounting1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Staff', 'position' => 'Accounting', 'email_verified_at' => now()],
            ['username' => 'inventory1', 'email' => 'inventory1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Staff', 'position' => 'Inventory', 'email_verified_at' => now()],
            ['username' => 'sales1', 'email' => 'sales1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Sales Representative', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'sales2', 'email' => 'sales2@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Sales Representative', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'operator1', 'email' => 'operator1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Operator', 'position' => 'System', 'email_verified_at' => now()],
            ['username' => 'analyst1', 'email' => 'analyst1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Analyst', 'position' => 'Data', 'email_verified_at' => now()],
            ['username' => 'coordinator1', 'email' => 'coordinator1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Coordinator', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'assistant1', 'email' => 'assistant1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Assistant', 'position' => 'Administration', 'email_verified_at' => now()],
            ['username' => 'lead1', 'email' => 'lead1@company.com', 'password' => Hash::make('user'), 'role' => 'viewer', 'job_title' => 'Team Lead', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'executive1', 'email' => 'executive1@company.com', 'password' => Hash::make('user'), 'role' => 'viewer', 'job_title' => 'Executive', 'position' => 'Sales', 'email_verified_at' => now()],
            ['username' => 'clerk1', 'email' => 'clerk1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Clerk', 'position' => 'Administration', 'email_verified_at' => now()],
            ['username' => 'support1', 'email' => 'support1@company.com', 'password' => Hash::make('user'), 'role' => 'useradmin', 'job_title' => 'Support', 'position' => 'Customer Service', 'email_verified_at' => now()],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
