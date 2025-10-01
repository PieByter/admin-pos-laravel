<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Resources (dalam bahasa Inggris)
        $resources = [
            'items',                // barang
            'suppliers',            // supplier  
            'customers',            // customer
            'units',               // satuan
            'item_categories',     // jenis_barang
            'item_groups',         // group_barang
            'unit_conversions',    // satuan_konversi
            'pre_purchase_orders', // po
            'purchase_orders',     // pembelian
            'sales_orders',        // penjualan
            'returns',             // retur
            'users',               // user
            'activity_logs',       // logs
            'transactions',        // transaksi
        ];

        // CRUD permissions
        $crudActions = ['view', 'create', 'update', 'delete'];

        // 1. Generate semua permission CRUD untuk setiap resource
        $allPermissions = [];
        foreach ($resources as $resource) {
            foreach ($crudActions as $action) {
                $permissionName = $resource . '_' . $action;
                $allPermissions[] = $permissionName;
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // 2. Permission level mapping untuk role
        $permissionLevels = [
            'none' => [],
            'view_only' => ['view'],
            'full' => ['view', 'create', 'update', 'delete'],
        ];

        // 3. Role presets dengan permission level
        $rolePresets = [
            'superadmin' => [
                'items' => 'full',
                'suppliers' => 'full',
                'customers' => 'full',
                'units' => 'full',
                'item_categories' => 'full',
                'item_groups' => 'full',
                'unit_conversions' => 'full',
                'pre_purchase_orders' => 'full',
                'purchase_orders' => 'full',
                'sales_orders' => 'full',
                'returns' => 'full',
                'users' => 'full',
                'activity_logs' => 'full',
                'transactions' => 'full',
            ],
            'useradmin' => [
                'items' => 'view_only',
                'suppliers' => 'view_only',
                'customers' => 'view_only',
                'units' => 'view_only',
                'item_categories' => 'view_only',
                'item_groups' => 'view_only',
                'unit_conversions' => 'view_only',
                'pre_purchase_orders' => 'view_only',
                'purchase_orders' => 'view_only',
                'sales_orders' => 'view_only',
                'returns' => 'view_only',
                'users' => 'view_only',
                'activity_logs' => 'view_only',
                'transactions' => 'view_only',
            ],
            'kasir' => [
                'items' => 'view_only',
                'suppliers' => 'none',
                'customers' => 'view_only',
                'units' => 'view_only',
                'item_categories' => 'none',
                'item_groups' => 'none',
                'unit_conversions' => 'none',
                'pre_purchase_orders' => 'none',
                'purchase_orders' => 'none',
                'sales_orders' => 'full',
                'returns' => 'full',
                'users' => 'none',
                'activity_logs' => 'none',
                'transactions' => 'full',
            ],
            'gudang' => [
                'items' => 'full',
                'suppliers' => 'full',
                'customers' => 'view_only',
                'units' => 'full',
                'item_categories' => 'full',
                'item_groups' => 'full',
                'unit_conversions' => 'full',
                'pre_purchase_orders' => 'full',
                'purchase_orders' => 'full',
                'sales_orders' => 'view_only',
                'returns' => 'view_only',
                'users' => 'none',
                'activity_logs' => 'none',
                'transactions' => 'none',
            ],
            'viewer' => [
                'items' => 'view_only',
                'suppliers' => 'view_only',
                'customers' => 'view_only',
                'units' => 'view_only',
                'item_categories' => 'view_only',
                'item_groups' => 'view_only',
                'unit_conversions' => 'view_only',
                'pre_purchase_orders' => 'view_only',
                'purchase_orders' => 'view_only',
                'sales_orders' => 'view_only',
                'returns' => 'view_only',
                'users' => 'view_only',
                'activity_logs' => 'view_only',
                'transactions' => 'view_only',
            ],
        ];

        // 4. Buat role dan assign permission sesuai preset
        foreach ($rolePresets as $roleName => $resourcePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $permissionsToAssign = [];

            foreach ($resourcePermissions as $resource => $level) {
                if ($level === 'none') continue;

                $actions = $permissionLevels[$level] ?? [];
                foreach ($actions as $action) {
                    $permissionsToAssign[] = $resource . '_' . $action;
                }
            }

            $role->syncPermissions($permissionsToAssign);
        }

        // 5. Output info (opsional)
        $this->command->info('Permissions created: ' . count($allPermissions));
        $this->command->info('Roles created: ' . count($rolePresets));

        $user = \App\Models\User::where('id', 1)->orWhere('email', 'admin@gmail.com')->first();
        if ($user) {
            $user->assignRole('superadmin');
            $this->command->info('Superadmin role assigned to user: ' . $user->email);
        } else {
            $this->command->warn('User with ID 1 or email admin@gmail.com not found!');
        }

        $this->command->info('Roles created: ' . count($rolePresets));
    }
}