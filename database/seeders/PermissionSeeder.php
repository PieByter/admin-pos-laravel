<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

// class PermissionSeeder extends Seeder
// {
//     /**
//      * Run the database seeds_
//      */
//     public function run(): void
//     {
//         $permissions = [
//             // Users Permissions
//             ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Can create new users'],
//             ['name' => 'users.read', 'display_name' => 'Read Users', 'description' => 'Can view users'],
//             ['name' => 'users.update', 'display_name' => 'Update Users', 'description' => 'Can update users'],
//             ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],

//             // Items Permissions
//             ['name' => 'items.create', 'display_name' => 'Create Items', 'description' => 'Can create new items'],
//             ['name' => 'items.read', 'display_name' => 'Read Items', 'description' => 'Can view items'],
//             ['name' => 'items.update', 'display_name' => 'Update Items', 'description' => 'Can update items'],
//             ['name' => 'items.delete', 'display_name' => 'Delete Items', 'description' => 'Can delete items'],

//             // Customers Permissions
//             ['name' => 'customers.create', 'display_name' => 'Create Customers', 'description' => 'Can create new customers'],
//             ['name' => 'customers.read', 'display_name' => 'Read Customers', 'description' => 'Can view customers'],
//             ['name' => 'customers.update', 'display_name' => 'Update Customers', 'description' => 'Can update customers'],
//             ['name' => 'customers.delete', 'display_name' => 'Delete Customers', 'description' => 'Can delete customers'],

//             // Suppliers Permissions
//             ['name' => 'suppliers.create', 'display_name' => 'Create Suppliers', 'description' => 'Can create new suppliers'],
//             ['name' => 'suppliers.read', 'display_name' => 'Read Suppliers', 'description' => 'Can view suppliers'],
//             ['name' => 'suppliers.update', 'display_name' => 'Update Suppliers', 'description' => 'Can update suppliers'],
//             ['name' => 'suppliers.delete', 'display_name' => 'Delete Suppliers', 'description' => 'Can delete suppliers'],

//             // Sales Orders Permissions
//             ['name' => 'sales.create', 'display_name' => 'Create Sales', 'description' => 'Can create sales orders'],
//             ['name' => 'sales.read', 'display_name' => 'Read Sales', 'description' => 'Can view sales orders'],
//             ['name' => 'sales.update', 'display_name' => 'Update Sales', 'description' => 'Can update sales orders'],
//             ['name' => 'sales.delete', 'display_name' => 'Delete Sales', 'description' => 'Can delete sales orders'],

//             // Purchase Orders Permissions
//             ['name' => 'purchases.create', 'display_name' => 'Create Purchases', 'description' => 'Can create purchase orders'],
//             ['name' => 'purchases.read', 'display_name' => 'Read Purchases', 'description' => 'Can view purchase orders'],
//             ['name' => 'purchases.update', 'display_name' => 'Update Purchases', 'description' => 'Can update purchase orders'],
//             ['name' => 'purchases.delete', 'display_name' => 'Delete Purchases', 'description' => 'Can delete purchase orders'],

//             // Pre-Purchase Orders Permissions
//             ['name' => 'pre_purchases.create', 'display_name' => 'Create Pre-Purchases', 'description' => 'Can create pre-purchase orders'],
//             ['name' => 'pre_purchases.read', 'display_name' => 'Read Pre-Purchases', 'description' => 'Can view pre-purchase orders'],
//             ['name' => 'pre_purchases.update', 'display_name' => 'Update Pre-Purchases', 'description' => 'Can update pre-purchase orders'],
//             ['name' => 'pre_purchases.delete', 'display_name' => 'Delete Pre-Purchases', 'description' => 'Can delete pre-purchase orders'],
//             ['name' => 'pre_purchases.approve', 'display_name' => 'Approve Pre-Purchases', 'description' => 'Can approve pre-purchase orders'],
//             ['name' => 'pre_purchases.reject', 'display_name' => 'Reject Pre-Purchases', 'description' => 'Can reject pre-purchase orders'],
//             ['name' => 'pre_purchases.convert', 'display_name' => 'Convert Pre-Purchases', 'description' => 'Can convert pre-purchases to purchase orders'],

//             // Returns Permissions
//             ['name' => 'returns.create', 'display_name' => 'Create Returns', 'description' => 'Can create return orders'],
//             ['name' => 'returns.read', 'display_name' => 'Read Returns', 'description' => 'Can view return orders'],
//             ['name' => 'returns.update', 'display_name' => 'Update Returns', 'description' => 'Can update return orders'],
//             ['name' => 'returns.delete', 'display_name' => 'Delete Returns', 'description' => 'Can delete return orders'],
//             ['name' => 'returns.approve', 'display_name' => 'Approve Returns', 'description' => 'Can approve return requests'],
//             ['name' => 'returns.reject', 'display_name' => 'Reject Returns', 'description' => 'Can reject return requests'],
//             ['name' => 'returns.process', 'display_name' => 'Process Returns', 'description' => 'Can process approved returns'],

//             // Inventory Permissions
//             ['name' => 'inventory.view', 'display_name' => 'View Inventory', 'description' => 'Can view inventory levels'],
//             ['name' => 'inventory.adjust', 'display_name' => 'Adjust Inventory', 'description' => 'Can adjust inventory stock'],
//             ['name' => 'inventory.transfer', 'display_name' => 'Transfer Inventory', 'description' => 'Can transfer inventory between locations'],

//             // Reports Permissions
//             ['name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'Can view all reports'],
//             ['name' => 'reports.sales', 'display_name' => 'Sales Reports', 'description' => 'Can view sales reports'],
//             ['name' => 'reports.purchases', 'display_name' => 'Purchase Reports', 'description' => 'Can view purchase reports'],
//             ['name' => 'reports.returns', 'display_name' => 'Return Reports', 'description' => 'Can view return reports'],
//             ['name' => 'reports.inventory', 'display_name' => 'Inventory Reports', 'description' => 'Can view inventory reports'],
//             ['name' => 'reports.financial', 'display_name' => 'Financial Reports', 'description' => 'Can view financial reports'],

//             // System Permissions
//             ['name' => 'system.settings', 'display_name' => 'System Settings', 'description' => 'Can manage system settings'],
//             ['name' => 'system.backup', 'display_name' => 'System Backup', 'description' => 'Can perform system backup'],
//             ['name' => 'system.logs', 'display_name' => 'View System Logs', 'description' => 'Can view system activity logs'],
//         ];

//         foreach ($permissions as $permission) {
//             DB::table('permissions')->insert(array_merge($permission, [
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]));
//         }
//     }
// }