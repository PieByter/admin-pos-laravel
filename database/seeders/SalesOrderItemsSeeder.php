<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesOrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesOrderItems = [
            // SO-2025-001 items (4 items)
            ['sales_order_id' => 1, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 22000.00, 'subtotal' => 110000.00],
            ['sales_order_id' => 1, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 3, 'base_quantity' => 3, 'sell_price' => 23000.00, 'subtotal' => 69000.00],
            ['sales_order_id' => 1, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 75000.00, 'subtotal' => 150000.00],
            ['sales_order_id' => 1, 'item_id' => 12, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 60000.00, 'subtotal' => 60000.00],

            // SO-2025-002 items (5 items)
            ['sales_order_id' => 2, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 3, 'base_quantity' => 3, 'sell_price' => 75000.00, 'subtotal' => 225000.00],
            ['sales_order_id' => 2, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 85000.00, 'subtotal' => 170000.00],
            ['sales_order_id' => 2, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 100000.00, 'subtotal' => 100000.00],
            ['sales_order_id' => 2, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 75000.00, 'subtotal' => 150000.00],
            ['sales_order_id' => 2, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 75000.00, 'subtotal' => 75000.00],

            // SO-2025-003 items (4 items)
            ['sales_order_id' => 3, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 24000.00, 'subtotal' => 144000.00],
            ['sales_order_id' => 3, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 25000.00, 'subtotal' => 100000.00],
            ['sales_order_id' => 3, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 85000.00, 'subtotal' => 170000.00],
            ['sales_order_id' => 3, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 100000.00, 'subtotal' => 100000.00],

            // SO-2025-004 items (5 items)
            ['sales_order_id' => 4, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'sell_price' => 22000.00, 'subtotal' => 176000.00],
            ['sales_order_id' => 4, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 23000.00, 'subtotal' => 138000.00],
            ['sales_order_id' => 4, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 23500.00, 'subtotal' => 117500.00],
            ['sales_order_id' => 4, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 22500.00, 'subtotal' => 90000.00],
            ['sales_order_id' => 4, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 3, 'base_quantity' => 3, 'sell_price' => 24000.00, 'subtotal' => 72000.00],

            // SO-2025-005 items (3 items)
            ['sales_order_id' => 5, 'item_id' => 17, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 75000.00, 'subtotal' => 150000.00],
            ['sales_order_id' => 5, 'item_id' => 46, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'sell_price' => 1500.00, 'subtotal' => 30000.00],
            ['sales_order_id' => 5, 'item_id' => 52, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'sell_price' => 1500.00, 'subtotal' => 22500.00],

            // SO-2025-006 items (4 items)
            ['sales_order_id' => 6, 'item_id' => 46, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'sell_price' => 1500.00, 'subtotal' => 37500.00],
            ['sales_order_id' => 6, 'item_id' => 48, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'sell_price' => 1500.00, 'subtotal' => 30000.00],
            ['sales_order_id' => 6, 'item_id' => 52, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'sell_price' => 1500.00, 'subtotal' => 27000.00],
            ['sales_order_id' => 6, 'item_id' => 54, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'sell_price' => 1500.00, 'subtotal' => 22500.00],

            // SO-2025-007 items (3 items)
            ['sales_order_id' => 7, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 100000.00, 'subtotal' => 200000.00],
            ['sales_order_id' => 7, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 100000.00, 'subtotal' => 100000.00],
            ['sales_order_id' => 7, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 75000.00, 'subtotal' => 75000.00],

            // SO-2025-008 items (5 items)
            ['sales_order_id' => 8, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 3, 'base_quantity' => 3, 'sell_price' => 23000.00, 'subtotal' => 69000.00],
            ['sales_order_id' => 8, 'item_id' => 12, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 60000.00, 'subtotal' => 60000.00],
            ['sales_order_id' => 8, 'item_id' => 18, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'sell_price' => 1500.00, 'subtotal' => 15000.00],
            ['sales_order_id' => 8, 'item_id' => 20, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'sell_price' => 1500.00, 'subtotal' => 12000.00],
            ['sales_order_id' => 8, 'item_id' => 25, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 1500.00, 'subtotal' => 9000.00],

            // SO-2025-009 items (4 items)
            ['sales_order_id' => 9, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 75000.00, 'subtotal' => 300000.00],
            ['sales_order_id' => 9, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 85000.00, 'subtotal' => 170000.00],
            ['sales_order_id' => 9, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 24000.00, 'subtotal' => 120000.00],
            ['sales_order_id' => 9, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 25000.00, 'subtotal' => 100000.00],

            // SO-2025-010 items (3 items)
            ['sales_order_id' => 10, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 75000.00, 'subtotal' => 75000.00],
            ['sales_order_id' => 10, 'item_id' => 17, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 75000.00, 'subtotal' => 75000.00],
            ['sales_order_id' => 10, 'item_id' => 30, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'sell_price' => 1500.00, 'subtotal' => 60000.00],

            // SO-2025-011 items (5 items)
            ['sales_order_id' => 11, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 22000.00, 'subtotal' => 132000.00],
            ['sales_order_id' => 11, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 23000.00, 'subtotal' => 92000.00],
            ['sales_order_id' => 11, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 85000.00, 'subtotal' => 85000.00],
            ['sales_order_id' => 11, 'item_id' => 35, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'sell_price' => 1500.00, 'subtotal' => 45000.00],
            ['sales_order_id' => 11, 'item_id' => 40, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'sell_price' => 1500.00, 'subtotal' => 37500.00],

            // SO-2025-012 items (4 items)
            ['sales_order_id' => 12, 'item_id' => 55, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'sell_price' => 1500.00, 'subtotal' => 52500.00],
            ['sales_order_id' => 12, 'item_id' => 60, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'sell_price' => 1500.00, 'subtotal' => 37500.00],
            ['sales_order_id' => 12, 'item_id' => 65, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'sell_price' => 1500.00, 'subtotal' => 30000.00],
            ['sales_order_id' => 12, 'item_id' => 18, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'sell_price' => 1500.00, 'subtotal' => 15000.00],

            // SO-2025-013 items (5 items)
            ['sales_order_id' => 13, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 75000.00, 'subtotal' => 375000.00],
            ['sales_order_id' => 13, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 100000.00, 'subtotal' => 200000.00],
            ['sales_order_id' => 13, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 1, 'base_quantity' => 1, 'sell_price' => 100000.00, 'subtotal' => 100000.00],
            ['sales_order_id' => 13, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'sell_price' => 22000.00, 'subtotal' => 220000.00],
            ['sales_order_id' => 13, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'sell_price' => 24000.00, 'subtotal' => 192000.00],

            // SO-2025-014 items (3 items)
            ['sales_order_id' => 14, 'item_id' => 19, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'sell_price' => 1500.00, 'subtotal' => 30000.00],
            ['sales_order_id' => 14, 'item_id' => 22, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'sell_price' => 1500.00, 'subtotal' => 22500.00],
            ['sales_order_id' => 14, 'item_id' => 27, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'sell_price' => 1500.00, 'subtotal' => 18000.00],

            // SO-2025-015 items (4 items)
            ['sales_order_id' => 15, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 3, 'base_quantity' => 3, 'sell_price' => 85000.00, 'subtotal' => 255000.00],
            ['sales_order_id' => 15, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 75000.00, 'subtotal' => 150000.00],
            ['sales_order_id' => 15, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 7, 'base_quantity' => 7, 'sell_price' => 23000.00, 'subtotal' => 161000.00],
            ['sales_order_id' => 15, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 24000.00, 'subtotal' => 144000.00],

            // SO-2025-016 items (5 items)
            ['sales_order_id' => 16, 'item_id' => 12, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 60000.00, 'subtotal' => 120000.00],
            ['sales_order_id' => 16, 'item_id' => 28, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'sell_price' => 1500.00, 'subtotal' => 27000.00],
            ['sales_order_id' => 16, 'item_id' => 32, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'sell_price' => 1500.00, 'subtotal' => 22500.00],
            ['sales_order_id' => 16, 'item_id' => 38, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'sell_price' => 1500.00, 'subtotal' => 18000.00],
            ['sales_order_id' => 16, 'item_id' => 42, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'sell_price' => 1500.00, 'subtotal' => 15000.00],

            // SO-2025-017 items (3 items)
            ['sales_order_id' => 17, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 3, 'base_quantity' => 3, 'sell_price' => 100000.00, 'subtotal' => 300000.00],
            ['sales_order_id' => 17, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 23000.00, 'subtotal' => 115000.00],
            ['sales_order_id' => 17, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 23500.00, 'subtotal' => 94000.00],

            // SO-2025-018 items (4 items)
            ['sales_order_id' => 18, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 75000.00, 'subtotal' => 150000.00],
            ['sales_order_id' => 18, 'item_id' => 24, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'sell_price' => 1500.00, 'subtotal' => 12000.00],
            ['sales_order_id' => 18, 'item_id' => 31, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 1500.00, 'subtotal' => 9000.00],
            ['sales_order_id' => 18, 'item_id' => 41, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 1500.00, 'subtotal' => 7500.00],

            // SO-2025-019 items (5 items)
            ['sales_order_id' => 19, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 4, 'base_quantity' => 4, 'sell_price' => 75000.00, 'subtotal' => 300000.00],
            ['sales_order_id' => 19, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 85000.00, 'subtotal' => 170000.00],
            ['sales_order_id' => 19, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'sell_price' => 22000.00, 'subtotal' => 176000.00],
            ['sales_order_id' => 19, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'sell_price' => 22500.00, 'subtotal' => 135000.00],
            ['sales_order_id' => 19, 'item_id' => 50, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'sell_price' => 1500.00, 'subtotal' => 37500.00],

            // SO-2025-020 items (4 items)
            ['sales_order_id' => 20, 'item_id' => 17, 'unit_id' => 1, 'quantity' => 2, 'base_quantity' => 2, 'sell_price' => 75000.00, 'subtotal' => 150000.00],
            ['sales_order_id' => 20, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 5, 'base_quantity' => 5, 'sell_price' => 25000.00, 'subtotal' => 125000.00],
            ['sales_order_id' => 20, 'item_id' => 33, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'sell_price' => 1500.00, 'subtotal' => 30000.00],
            ['sales_order_id' => 20, 'item_id' => 45, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'sell_price' => 1500.00, 'subtotal' => 22500.00],
        ];

        foreach ($salesOrderItems as $item) {
            DB::table('sales_order_items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
