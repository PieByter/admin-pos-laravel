<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseOrderItems = [
            // PO-2025-001 items (5 items)
            ['purchase_order_id' => 1, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 50, 'base_quantity' => 50, 'buy_price' => 20000.00, 'subtotal' => 1000000.00],
            ['purchase_order_id' => 1, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'buy_price' => 21000.00, 'subtotal' => 840000.00],
            ['purchase_order_id' => 1, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'buy_price' => 22000.00, 'subtotal' => 660000.00],
            ['purchase_order_id' => 1, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'buy_price' => 23000.00, 'subtotal' => 575000.00],
            ['purchase_order_id' => 1, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'buy_price' => 21000.00, 'subtotal' => 735000.00],

            // PO-2025-002 items (4 items) - Coffee products
            ['purchase_order_id' => 2, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'buy_price' => 65000.00, 'subtotal' => 975000.00],
            ['purchase_order_id' => 2, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'buy_price' => 75000.00, 'subtotal' => 900000.00],
            ['purchase_order_id' => 2, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'buy_price' => 85000.00, 'subtotal' => 680000.00],
            ['purchase_order_id' => 2, 'item_id' => 12, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'buy_price' => 50000.00, 'subtotal' => 500000.00],

            // PO-2025-003 items (6 items) - Cigarettes
            ['purchase_order_id' => 3, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'buy_price' => 20000.00, 'subtotal' => 1600000.00],
            ['purchase_order_id' => 3, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 70, 'base_quantity' => 70, 'buy_price' => 21000.00, 'subtotal' => 1470000.00],
            ['purchase_order_id' => 3, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 60, 'base_quantity' => 60, 'buy_price' => 21500.00, 'subtotal' => 1290000.00],
            ['purchase_order_id' => 3, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 45, 'base_quantity' => 45, 'buy_price' => 20500.00, 'subtotal' => 922500.00],
            ['purchase_order_id' => 3, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'buy_price' => 22000.00, 'subtotal' => 880000.00],
            ['purchase_order_id' => 3, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'buy_price' => 23000.00, 'subtotal' => 805000.00],

            // PO-2025-004 items (5 items) - Tea products
            ['purchase_order_id' => 4, 'item_id' => 46, 'unit_id' => 1, 'quantity' => 200, 'base_quantity' => 200, 'buy_price' => 1200.00, 'subtotal' => 240000.00],
            ['purchase_order_id' => 4, 'item_id' => 48, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'buy_price' => 1200.00, 'subtotal' => 216000.00],
            ['purchase_order_id' => 4, 'item_id' => 52, 'unit_id' => 1, 'quantity' => 150, 'base_quantity' => 150, 'buy_price' => 1200.00, 'subtotal' => 180000.00],
            ['purchase_order_id' => 4, 'item_id' => 54, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'buy_price' => 1200.00, 'subtotal' => 144000.00],
            ['purchase_order_id' => 4, 'item_id' => 60, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'buy_price' => 1200.00, 'subtotal' => 120000.00],

            // PO-2025-005 items (4 items) - Premium coffee
            ['purchase_order_id' => 5, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'buy_price' => 75000.00, 'subtotal' => 750000.00],
            ['purchase_order_id' => 5, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'buy_price' => 85000.00, 'subtotal' => 680000.00],
            ['purchase_order_id' => 5, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'buy_price' => 65000.00, 'subtotal' => 780000.00],
            ['purchase_order_id' => 5, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'buy_price' => 65000.00, 'subtotal' => 975000.00],

            // PO-2025-006 items (5 items) - Cigarette restock
            ['purchase_order_id' => 6, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 90, 'base_quantity' => 90, 'buy_price' => 21000.00, 'subtotal' => 1890000.00],
            ['purchase_order_id' => 6, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 75, 'base_quantity' => 75, 'buy_price' => 22000.00, 'subtotal' => 1650000.00],
            ['purchase_order_id' => 6, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 50, 'base_quantity' => 50, 'buy_price' => 20500.00, 'subtotal' => 1025000.00],
            ['purchase_order_id' => 6, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 60, 'base_quantity' => 60, 'buy_price' => 20000.00, 'subtotal' => 1200000.00],
            ['purchase_order_id' => 6, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'buy_price' => 22000.00, 'subtotal' => 660000.00],

            // PO-2025-007 items (6 items) - Snacks and beverages
            ['purchase_order_id' => 7, 'item_id' => 18, 'unit_id' => 1, 'quantity' => 300, 'base_quantity' => 300, 'buy_price' => 1200.00, 'subtotal' => 360000.00],
            ['purchase_order_id' => 7, 'item_id' => 20, 'unit_id' => 1, 'quantity' => 250, 'base_quantity' => 250, 'buy_price' => 1200.00, 'subtotal' => 300000.00],
            ['purchase_order_id' => 7, 'item_id' => 25, 'unit_id' => 1, 'quantity' => 200, 'base_quantity' => 200, 'buy_price' => 1200.00, 'subtotal' => 240000.00],
            ['purchase_order_id' => 7, 'item_id' => 30, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'buy_price' => 1200.00, 'subtotal' => 216000.00],
            ['purchase_order_id' => 7, 'item_id' => 35, 'unit_id' => 1, 'quantity' => 150, 'base_quantity' => 150, 'buy_price' => 1200.00, 'subtotal' => 180000.00],
            ['purchase_order_id' => 7, 'item_id' => 40, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'buy_price' => 1200.00, 'subtotal' => 144000.00],

            // PO-2025-008 items (4 items) - Emergency stock
            ['purchase_order_id' => 8, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'buy_price' => 65000.00, 'subtotal' => 1300000.00],
            ['purchase_order_id' => 8, 'item_id' => 17, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'buy_price' => 65000.00, 'subtotal' => 975000.00],
            ['purchase_order_id' => 8, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'buy_price' => 21500.00, 'subtotal' => 537500.00],
            ['purchase_order_id' => 8, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 30, 'base_quantity' => 30, 'buy_price' => 21000.00, 'subtotal' => 630000.00],

            // PO-2025-009 items (5 items) - Q4 coffee bulk
            ['purchase_order_id' => 9, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 25, 'base_quantity' => 25, 'buy_price' => 75000.00, 'subtotal' => 1875000.00],
            ['purchase_order_id' => 9, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'buy_price' => 85000.00, 'subtotal' => 1700000.00],
            ['purchase_order_id' => 9, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'buy_price' => 75000.00, 'subtotal' => 1125000.00],
            ['purchase_order_id' => 9, 'item_id' => 14, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'buy_price' => 85000.00, 'subtotal' => 1020000.00],
            ['purchase_order_id' => 9, 'item_id' => 12, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'buy_price' => 50000.00, 'subtotal' => 900000.00],

            // PO-2025-010 items (5 items) - Mixed products
            ['purchase_order_id' => 10, 'item_id' => 15, 'unit_id' => 1, 'quantity' => 10, 'base_quantity' => 10, 'buy_price' => 65000.00, 'subtotal' => 650000.00],
            ['purchase_order_id' => 10, 'item_id' => 16, 'unit_id' => 1, 'quantity' => 8, 'base_quantity' => 8, 'buy_price' => 65000.00, 'subtotal' => 520000.00],
            ['purchase_order_id' => 10, 'item_id' => 22, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'buy_price' => 1200.00, 'subtotal' => 120000.00],
            ['purchase_order_id' => 10, 'item_id' => 28, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'buy_price' => 1200.00, 'subtotal' => 96000.00],
            ['purchase_order_id' => 10, 'item_id' => 32, 'unit_id' => 1, 'quantity' => 60, 'base_quantity' => 60, 'buy_price' => 1200.00, 'subtotal' => 72000.00],

            // PO-2025-011 items (6 items) - Year-end preparation
            ['purchase_order_id' => 11, 'item_id' => 1, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'buy_price' => 20000.00, 'subtotal' => 2000000.00],
            ['purchase_order_id' => 11, 'item_id' => 2, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'buy_price' => 21000.00, 'subtotal' => 1680000.00],
            ['purchase_order_id' => 11, 'item_id' => 9, 'unit_id' => 1, 'quantity' => 20, 'base_quantity' => 20, 'buy_price' => 65000.00, 'subtotal' => 1300000.00],
            ['purchase_order_id' => 11, 'item_id' => 10, 'unit_id' => 1, 'quantity' => 15, 'base_quantity' => 15, 'buy_price' => 75000.00, 'subtotal' => 1125000.00],
            ['purchase_order_id' => 11, 'item_id' => 38, 'unit_id' => 1, 'quantity' => 200, 'base_quantity' => 200, 'buy_price' => 1200.00, 'subtotal' => 240000.00],
            ['purchase_order_id' => 11, 'item_id' => 42, 'unit_id' => 1, 'quantity' => 150, 'base_quantity' => 150, 'buy_price' => 1200.00, 'subtotal' => 180000.00],

            // PO-2025-012 items (4 items) - Tea variety expansion
            ['purchase_order_id' => 12, 'item_id' => 55, 'unit_id' => 1, 'quantity' => 180, 'base_quantity' => 180, 'buy_price' => 1200.00, 'subtotal' => 216000.00],
            ['purchase_order_id' => 12, 'item_id' => 65, 'unit_id' => 1, 'quantity' => 150, 'base_quantity' => 150, 'buy_price' => 1200.00, 'subtotal' => 180000.00],
            ['purchase_order_id' => 12, 'item_id' => 19, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'buy_price' => 1200.00, 'subtotal' => 144000.00],
            ['purchase_order_id' => 12, 'item_id' => 24, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'buy_price' => 1200.00, 'subtotal' => 120000.00],

            // PO-2025-013 items (4 items) - Premium cigarettes
            ['purchase_order_id' => 13, 'item_id' => 3, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'buy_price' => 22000.00, 'subtotal' => 2640000.00],
            ['purchase_order_id' => 13, 'item_id' => 4, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'buy_price' => 23000.00, 'subtotal' => 2300000.00],
            ['purchase_order_id' => 13, 'item_id' => 7, 'unit_id' => 1, 'quantity' => 80, 'base_quantity' => 80, 'buy_price' => 20500.00, 'subtotal' => 1640000.00],
            ['purchase_order_id' => 13, 'item_id' => 8, 'unit_id' => 1, 'quantity' => 60, 'base_quantity' => 60, 'buy_price' => 22000.00, 'subtotal' => 1320000.00],

            // PO-2025-014 items (3 items) - New supplier trial
            ['purchase_order_id' => 14, 'item_id' => 31, 'unit_id' => 1, 'quantity' => 200, 'base_quantity' => 200, 'buy_price' => 1200.00, 'subtotal' => 240000.00],
            ['purchase_order_id' => 14, 'item_id' => 41, 'unit_id' => 1, 'quantity' => 150, 'base_quantity' => 150, 'buy_price' => 1200.00, 'subtotal' => 180000.00],
            ['purchase_order_id' => 14, 'item_id' => 50, 'unit_id' => 1, 'quantity' => 120, 'base_quantity' => 120, 'buy_price' => 1200.00, 'subtotal' => 144000.00],

            // PO-2025-015 items (5 items) - October preparation
            ['purchase_order_id' => 15, 'item_id' => 11, 'unit_id' => 1, 'quantity' => 18, 'base_quantity' => 18, 'buy_price' => 85000.00, 'subtotal' => 1530000.00],
            ['purchase_order_id' => 15, 'item_id' => 13, 'unit_id' => 1, 'quantity' => 12, 'base_quantity' => 12, 'buy_price' => 75000.00, 'subtotal' => 900000.00],
            ['purchase_order_id' => 15, 'item_id' => 5, 'unit_id' => 1, 'quantity' => 40, 'base_quantity' => 40, 'buy_price' => 21000.00, 'subtotal' => 840000.00],
            ['purchase_order_id' => 15, 'item_id' => 6, 'unit_id' => 1, 'quantity' => 35, 'base_quantity' => 35, 'buy_price' => 21500.00, 'subtotal' => 752500.00],
            ['purchase_order_id' => 15, 'item_id' => 33, 'unit_id' => 1, 'quantity' => 100, 'base_quantity' => 100, 'buy_price' => 1200.00, 'subtotal' => 120000.00],
        ];

        foreach ($purchaseOrderItems as $item) {
            DB::table('purchase_order_items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
