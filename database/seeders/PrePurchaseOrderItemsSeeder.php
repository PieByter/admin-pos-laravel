<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrePurchaseOrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prePurchaseOrderItems = [
            // PPO-2025-001 items (4 items)
            ['pre_purchase_order_id' => 1, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 45, 'base_quantity' => 45, 'price' => 20000.00, 'subtotal' => 900000.00],
            ['pre_purchase_order_id' => 1, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'price' => 21000.00, 'subtotal' => 735000.00],
            ['pre_purchase_order_id' => 1, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'price' => 22000.00, 'subtotal' => 660000.00],
            ['pre_purchase_order_id' => 1, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'price' => 23000.00, 'subtotal' => 575000.00],

            // PPO-2025-002 items (3 items)
            ['pre_purchase_order_id' => 2, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'price' => 65000.00, 'subtotal' => 780000.00],
            ['pre_purchase_order_id' => 2, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'price' => 75000.00, 'subtotal' => 750000.00],
            ['pre_purchase_order_id' => 2, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'price' => 85000.00, 'subtotal' => 680000.00],

            // PPO-2025-003 items (5 items)
            ['pre_purchase_order_id' => 3, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 75, 'base_quantity' => 75, 'price' => 20000.00, 'subtotal' => 1500000.00],
            ['pre_purchase_order_id' => 3, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 65, 'base_quantity' => 65, 'price' => 21000.00, 'subtotal' => 1365000.00],
            ['pre_purchase_order_id' => 3, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 55, 'base_quantity' => 55, 'price' => 21500.00, 'subtotal' => 1182500.00],
            ['pre_purchase_order_id' => 3, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'price' => 20500.00, 'subtotal' => 820000.00],
            ['pre_purchase_order_id' => 3, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'price' => 22000.00, 'subtotal' => 770000.00],

            // PPO-2025-004 items (4 items)
            ['pre_purchase_order_id' => 4, 'item_id' => 46, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'price' => 1200.00, 'subtotal' => 216000.00],
            ['pre_purchase_order_id' => 4, 'item_id' => 48, 'unit_id' => 1, 'quantity' => 160, 'base_quantity' => 160, 'price' => 1200.00, 'subtotal' => 192000.00],
            ['pre_purchase_order_id' => 4, 'item_id' => 52, 'unit_id' => 1, 'quantity' => 140, 'base_quantity' => 140, 'price' => 1200.00, 'subtotal' => 168000.00],
            ['pre_purchase_order_id' => 4, 'item_id' => 54, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'price' => 1200.00, 'subtotal' => 144000.00],

            // PPO-2025-005 items (3 items)
            ['pre_purchase_order_id' => 5, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'price' => 75000.00, 'subtotal' => 600000.00],
            ['pre_purchase_order_id' => 5, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'price' => 85000.00, 'subtotal' => 510000.00],
            ['pre_purchase_order_id' => 5, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'price' => 65000.00, 'subtotal' => 650000.00],

            // PPO-2025-006 items (4 items)
            ['pre_purchase_order_id' => 6, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'price' => 21000.00, 'subtotal' => 1680000.00],
            ['pre_purchase_order_id' => 6, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 70, 'base_quantity' => 70, 'price' => 22000.00, 'subtotal' => 1540000.00],
            ['pre_purchase_order_id' => 6, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 45, 'base_quantity' => 45, 'price' => 20500.00, 'subtotal' => 922500.00],
            ['pre_purchase_order_id' => 6, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 55, 'base_quantity' => 55, 'price' => 20000.00, 'subtotal' => 1100000.00],

            // PPO-2025-007 items (5 items)
            ['pre_purchase_order_id' => 7, 'item_id' => 18, 'unit_id' => 1, 'quantity' => 250, 'base_quantity' => 250, 'price' => 1200.00, 'subtotal' => 300000.00],
            ['pre_purchase_order_id' => 7, 'item_id' => 20, 'unit_id' => 1, 'quantity' => 200, 'base_quantity' => 200, 'price' => 1200.00, 'subtotal' => 240000.00],
            ['pre_purchase_order_id' => 7, 'item_id' => 25, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'price' => 1200.00, 'subtotal' => 216000.00],
            ['pre_purchase_order_id' => 7, 'item_id' => 30, 'unit_id' => 1, 'quantity' => 160, 'base_quantity' => 160, 'price' => 1200.00, 'subtotal' => 192000.00],
            ['pre_purchase_order_id' => 7, 'item_id' => 35, 'unit_id' => 1, 'quantity' => 140, 'base_quantity' => 140, 'price' => 1200.00, 'subtotal' => 168000.00],

            // PPO-2025-008 items (3 items)
            ['pre_purchase_order_id' => 8, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'price' => 65000.00, 'subtotal' => 1170000.00],
            ['pre_purchase_order_id' => 8, 'item_id' => 17, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'price' => 65000.00, 'subtotal' => 780000.00],
            ['pre_purchase_order_id' => 8, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'price' => 21500.00, 'subtotal' => 430000.00],

            // PPO-2025-009 items (4 items)
            ['pre_purchase_order_id' => 9, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'price' => 75000.00, 'subtotal' => 1500000.00],
            ['pre_purchase_order_id' => 9, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'price' => 85000.00, 'subtotal' => 1530000.00],
            ['pre_purchase_order_id' => 9, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'price' => 75000.00, 'subtotal' => 900000.00],
            ['pre_purchase_order_id' => 9, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'price' => 85000.00, 'subtotal' => 850000.00],

            // PPO-2025-010 items (5 items)
            ['pre_purchase_order_id' => 10, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'price' => 65000.00, 'subtotal' => 520000.00],
            ['pre_purchase_order_id' => 10, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 6, 'base_quantity' => 6, 'price' => 65000.00, 'subtotal' => 390000.00],
            ['pre_purchase_order_id' => 10, 'item_id' => 22, 'unit_id' => 1, 'quantity' => 90, 'base_quantity' => 90, 'price' => 1200.00, 'subtotal' => 108000.00],
            ['pre_purchase_order_id' => 10, 'item_id' => 28, 'unit_id' => 1, 'quantity' => 70, 'base_quantity' => 70, 'price' => 1200.00, 'subtotal' => 84000.00],
            ['pre_purchase_order_id' => 10, 'item_id' => 32, 'unit_id' => 1, 'quantity' => 50, 'base_quantity' => 50, 'price' => 1200.00, 'subtotal' => 60000.00],

            // PPO-2025-011 items (5 items)
            ['pre_purchase_order_id' => 11, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 90, 'base_quantity' => 90, 'price' => 20000.00, 'subtotal' => 1800000.00],
            ['pre_purchase_order_id' => 11, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 70, 'base_quantity' => 70, 'price' => 21000.00, 'subtotal' => 1470000.00],
            ['pre_purchase_order_id' => 11, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'price' => 65000.00, 'subtotal' => 1170000.00],
            ['pre_purchase_order_id' => 11, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'price' => 75000.00, 'subtotal' => 900000.00],
            ['pre_purchase_order_id' => 11, 'item_id' => 38, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'price' => 1200.00, 'subtotal' => 216000.00],

            // PPO-2025-012 items (3 items)
            ['pre_purchase_order_id' => 12, 'item_id' => 55, 'unit_id' => 1, 'quantity' => 160, 'base_quantity' => 160, 'price' => 1200.00, 'subtotal' => 192000.00],
            ['pre_purchase_order_id' => 12, 'item_id' => 65, 'unit_id' => 1, 'quantity' => 140, 'base_quantity' => 140, 'price' => 1200.00, 'subtotal' => 168000.00],
            ['pre_purchase_order_id' => 12, 'item_id' => 19, 'unit_id' => 1, 'quantity' => 110, 'base_quantity' => 110, 'price' => 1200.00, 'subtotal' => 132000.00],

            // PPO-2025-013 items (4 items)
            ['pre_purchase_order_id' => 13, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 110, 'base_quantity' => 110, 'price' => 22000.00, 'subtotal' => 2420000.00],
            ['pre_purchase_order_id' => 13, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 90, 'base_quantity' => 90, 'price' => 23000.00, 'subtotal' => 2070000.00],
            ['pre_purchase_order_id' => 13, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 70, 'base_quantity' => 70, 'price' => 20500.00, 'subtotal' => 1435000.00],
            ['pre_purchase_order_id' => 13, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 50, 'base_quantity' => 50, 'price' => 22000.00, 'subtotal' => 1100000.00],

            // PPO-2025-014 items (3 items)
            ['pre_purchase_order_id' => 14, 'item_id' => 31, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'price' => 1200.00, 'subtotal' => 216000.00],
            ['pre_purchase_order_id' => 14, 'item_id' => 41, 'unit_id' => 1, 'quantity' => 140, 'base_quantity' => 140, 'price' => 1200.00, 'subtotal' => 168000.00],
            ['pre_purchase_order_id' => 14, 'item_id' => 50, 'unit_id' => 1, 'quantity' => 110, 'base_quantity' => 110, 'price' => 1200.00, 'subtotal' => 132000.00],

            // PPO-2025-015 items (4 items)
            ['pre_purchase_order_id' => 15, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 16, 'base_quantity' => 16, 'price' => 85000.00, 'subtotal' => 1360000.00],
            ['pre_purchase_order_id' => 15, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'price' => 75000.00, 'subtotal' => 750000.00],
            ['pre_purchase_order_id' => 15, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'price' => 21000.00, 'subtotal' => 735000.00],
            ['pre_purchase_order_id' => 15, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'price' => 21500.00, 'subtotal' => 645000.00],

            // PPO-2025-016 items (4 items)
            ['pre_purchase_order_id' => 16, 'item_id' => 12, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'price' => 50000.00, 'subtotal' => 750000.00],
            ['pre_purchase_order_id' => 16, 'item_id' => 33, 'unit_id' => 1, 'quantity' => 90, 'base_quantity' => 90, 'price' => 1200.00, 'subtotal' => 108000.00],
            ['pre_purchase_order_id' => 16, 'item_id' => 39, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'price' => 1200.00, 'subtotal' => 96000.00],
            ['pre_purchase_order_id' => 16, 'item_id' => 45, 'unit_id' => 1, 'quantity' => 70, 'base_quantity' => 70, 'price' => 1200.00, 'subtotal' => 84000.00],

            // PPO-2025-017 items (5 items)
            ['pre_purchase_order_id' => 17, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'price' => 65000.00, 'subtotal' => 1170000.00],
            ['pre_purchase_order_id' => 17, 'item_id' => 17, 'unit_id' => 1, 'quantity' => 14, 'base_quantity' => 14, 'price' => 65000.00, 'subtotal' => 910000.00],
            ['pre_purchase_order_id' => 17, 'item_id' => 21, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'price' => 1200.00, 'subtotal' => 144000.00],
            ['pre_purchase_order_id' => 17, 'item_id' => 26, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'price' => 1200.00, 'subtotal' => 120000.00],
            ['pre_purchase_order_id' => 17, 'item_id' => 29, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'price' => 1200.00, 'subtotal' => 96000.00],

            // PPO-2025-018 items (3 items)
            ['pre_purchase_order_id' => 18, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'price' => 20500.00, 'subtotal' => 820000.00],
            ['pre_purchase_order_id' => 18, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'price' => 22000.00, 'subtotal' => 660000.00],
            ['pre_purchase_order_id' => 18, 'item_id' => 34, 'unit_id' => 1, 'quantity' => 60, 'base_quantity' => 60, 'price' => 1200.00, 'subtotal' => 72000.00],

            // PPO-2025-019 items (4 items)
            ['pre_purchase_order_id' => 19, 'item_id' => 56, 'unit_id' => 1, 'quantity' => 200, 'base_quantity' => 200, 'price' => 1200.00, 'subtotal' => 240000.00],
            ['pre_purchase_order_id' => 19, 'item_id' => 58, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'price' => 1200.00, 'subtotal' => 216000.00],
            ['pre_purchase_order_id' => 19, 'item_id' => 61, 'unit_id' => 1, 'quantity' => 160, 'base_quantity' => 160, 'price' => 1200.00, 'subtotal' => 192000.00],
            ['pre_purchase_order_id' => 19, 'item_id' => 63, 'unit_id' => 1, 'quantity' => 140, 'base_quantity' => 140, 'price' => 1200.00, 'subtotal' => 168000.00],

            // PPO-2025-020 items (5 items)
            ['pre_purchase_order_id' => 20, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'price' => 65000.00, 'subtotal' => 1625000.00],
            ['pre_purchase_order_id' => 20, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'price' => 75000.00, 'subtotal' => 1500000.00],
            ['pre_purchase_order_id' => 20, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'price' => 85000.00, 'subtotal' => 1275000.00],
            ['pre_purchase_order_id' => 20, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 50, 'base_quantity' => 50, 'price' => 20000.00, 'subtotal' => 1000000.00],
            ['pre_purchase_order_id' => 20, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'price' => 21000.00, 'subtotal' => 840000.00],
        ];

        foreach ($prePurchaseOrderItems as $item) {
            DB::table('pre_purchase_order_items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
