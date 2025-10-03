<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['item_code' => 'STTC-001', 'item_name' => 'Union', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 18000.00, 'sell_price' => 22000.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-002', 'item_name' => 'Casual Hero', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 19000.00, 'sell_price' => 23000.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-003', 'item_name' => 'Marcopolo', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 20000.00, 'sell_price' => 24000.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-004', 'item_name' => 'Kennedy Blend of USA', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 21000.00, 'sell_price' => 25000.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-005', 'item_name' => 'Hollywood', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 19000.00, 'sell_price' => 23000.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-006', 'item_name' => 'Jazz Special Blend', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 19500.00, 'sell_price' => 23500.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-007', 'item_name' => 'Slims', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 18500.00, 'sell_price' => 22500.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'STTC-008', 'item_name' => 'Tri Happiness', 'item_category_id' => 6, 'item_group_id' => 5, 'unit_id' => 1, 'buy_price' => 20000.00, 'sell_price' => 24000.00, 'stock' => 0, 'item_description' => 'Rokok PT STTC'],
            ['item_code' => 'INC-001', 'item_name' => 'Indocafe Original Blend', 'item_category_id' => 1, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-002', 'item_name' => 'Indocafe Fine Blend', 'item_category_id' => 1, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 60000.00, 'sell_price' => 85000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-003', 'item_name' => 'Indocafe Coffee-O', 'item_category_id' => 1, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 75000.00, 'sell_price' => 100000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-004', 'item_name' => 'Indocafe Coffeemix', 'item_category_id' => 1, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 40000.00, 'sell_price' => 60000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-005', 'item_name' => 'Indocafe Coffeemix Ginseng', 'item_category_id' => 2, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 60000.00, 'sell_price' => 85000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-006', 'item_name' => 'Indocafe Coffeemix Jahe Ginger', 'item_category_id' => 4, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 75000.00, 'sell_price' => 100000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-007', 'item_name' => 'Indocafe Cappuccino', 'item_category_id' => 5, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-008', 'item_name' => 'Indocafe Caffe Latte', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-009', 'item_name' => 'Indocafe White', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-010', 'item_name' => 'Indocafe White Brown Sugar', 'item_category_id' => 3, 'item_group_id' => 3, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-011', 'item_name' => 'Indocafe White Vanilla', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-012', 'item_name' => 'Indocafe White Hazelnut', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-013', 'item_name' => 'Indocafe White Red Bean', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-014', 'item_name' => 'Indocafe Double Happiness', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-015', 'item_name' => 'Indocafe Tri Happiness', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-016', 'item_name' => 'Express Cafe Kopi Manis', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-017', 'item_name' => 'Express Cafe Kopi Susu', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-018', 'item_name' => 'Express Cafe Mocha', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-019', 'item_name' => 'MASTER Kopi Susu', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-020', 'item_name' => 'MASTER Kopi Mocha', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-021', 'item_name' => 'Javacafe Latte', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-022', 'item_name' => 'Javacafe Mocha', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-023', 'item_name' => 'Indocafe Coffeemix Mild', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-024', 'item_name' => 'Indocafe Coffeemix Rich & Strong', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-025', 'item_name' => 'House of Java', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-026', 'item_name' => 'Java Cafe Kopi Instan', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-027', 'item_name' => 'House of Toraja', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-028', 'item_name' => 'House of Mandheling', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-029', 'item_name' => 'House of Sumatra', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-030', 'item_name' => 'Java Instant Coffee', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-031', 'item_name' => 'Blue Mountain Kopi Instant', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-032', 'item_name' => 'Salute Instant Coffee', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-033', 'item_name' => 'MCoffee Instant Coffee', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-034', 'item_name' => 'Coffee Plus Instant Coffee', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-035', 'item_name' => 'Maxcafe Instant Coffee', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-036', 'item_name' => 'Indocafe Latte Mandheling Origins', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-037', 'item_name' => 'Indocafe Latte Gayo Origins', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-038', 'item_name' => 'Indocafe The Java Drip Coffee Pouch', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-039', 'item_name' => 'Indocafe The Bali Drip Coffee Pouch', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-040', 'item_name' => 'Indocafe The Sumatra Mandheling Drip Coffee Pouch', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-041', 'item_name' => 'Indocafe Sumatra', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-042', 'item_name' => 'Indocafe Sumatra Gold', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-043', 'item_name' => 'Indocafe The Flores Komodo Island Drip Coffee Pouch', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-044', 'item_name' => 'Indocafe The Sumatra Civet Drip Coffee Pouch', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-045', 'item_name' => 'Indocafe The Sumatra Organic Drip Coffee Pouch', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-046', 'item_name' => 'MaxTea Tarikk', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-047', 'item_name' => 'MaxTea Tarikk + Jahe', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-048', 'item_name' => 'MaxTea Lemon Tea', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-049', 'item_name' => 'MaxTea Apple Tea', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-050', 'item_name' => 'MaxTea Peach Tea', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-051', 'item_name' => 'TEAPLUS', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-052', 'item_name' => 'Salute Tea', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-053', 'item_name' => 'Airport Teh Instan', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-054', 'item_name' => 'MaxTea Milk Tea', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-055', 'item_name' => 'MaxTea Matcha Latte', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-056', 'item_name' => 'INDO CREAMER', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-057', 'item_name' => 'MaxCreamer', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-058', 'item_name' => 'Blue Mountain Creamer', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-059', 'item_name' => 'Indocafe Ginseng Cereal', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-060', 'item_name' => 'Chococino', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-061', 'item_name' => 'Chococino Light', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-062', 'item_name' => 'Koko Kopi Coklat', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-063', 'item_name' => 'Java Cocoa', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-064', 'item_name' => 'IndoKoko Chocolate', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
            ['item_code' => 'INC-065', 'item_name' => 'IndoSereal Chocolate', 'item_category_id' => 3, 'item_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'item_description' => 'Produk Incofood'],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
