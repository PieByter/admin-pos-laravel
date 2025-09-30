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
            ['items_code' => 'STTC-001', 'items_name' => 'Union', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 18000.00, 'sell_price' => 22000.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-002', 'items_name' => 'Casual Hero', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 19000.00, 'sell_price' => 23000.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-003', 'items_name' => 'Marcopolo', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 20000.00, 'sell_price' => 24000.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-004', 'items_name' => 'Kennedy Blend of USA', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 21000.00, 'sell_price' => 25000.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-005', 'items_name' => 'Hollywood', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 19000.00, 'sell_price' => 23000.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-006', 'items_name' => 'Jazz Special Blend', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 19500.00, 'sell_price' => 23500.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-007', 'items_name' => 'Slims', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 18500.00, 'sell_price' => 22500.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'STTC-008', 'items_name' => 'Tri Happiness', 'items_category_id' => 6, 'items_group_id' => 5, 'unit_id' => 1, 'buy_price' => 20000.00, 'sell_price' => 24000.00, 'stock' => 0, 'items_description' => 'Rokok PT STTC'],
            ['items_code' => 'INC-001', 'items_name' => 'Indocafe Original Blend', 'items_category_id' => 1, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-002', 'items_name' => 'Indocafe Fine Blend', 'items_category_id' => 1, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 60000.00, 'sell_price' => 85000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-003', 'items_name' => 'Indocafe Coffee-O', 'items_category_id' => 1, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 75000.00, 'sell_price' => 100000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-004', 'items_name' => 'Indocafe Coffeemix', 'items_category_id' => 1, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 40000.00, 'sell_price' => 60000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-005', 'items_name' => 'Indocafe Coffeemix Ginseng', 'items_category_id' => 2, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 60000.00, 'sell_price' => 85000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-006', 'items_name' => 'Indocafe Coffeemix Jahe Ginger', 'items_category_id' => 4, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 75000.00, 'sell_price' => 100000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-007', 'items_name' => 'Indocafe Cappuccino', 'items_category_id' => 5, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-008', 'items_name' => 'Indocafe Caffe Latte', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-009', 'items_name' => 'Indocafe White', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 50000.00, 'sell_price' => 75000.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-010', 'items_name' => 'Indocafe White Brown Sugar', 'items_category_id' => 3, 'items_group_id' => 3, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-011', 'items_name' => 'Indocafe White Vanilla', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-012', 'items_name' => 'Indocafe White Hazelnut', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-013', 'items_name' => 'Indocafe White Red Bean', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-014', 'items_name' => 'Indocafe Double Happiness', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-015', 'items_name' => 'Indocafe Tri Happiness', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-016', 'items_name' => 'Express Cafe Kopi Manis', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-017', 'items_name' => 'Express Cafe Kopi Susu', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-018', 'items_name' => 'Express Cafe Mocha', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-019', 'items_name' => 'MASTER Kopi Susu', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-020', 'items_name' => 'MASTER Kopi Mocha', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-021', 'items_name' => 'Javacafe Latte', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-022', 'items_name' => 'Javacafe Mocha', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-023', 'items_name' => 'Indocafe Coffeemix Mild', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-024', 'items_name' => 'Indocafe Coffeemix Rich & Strong', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-025', 'items_name' => 'House of Java', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-026', 'items_name' => 'Java Cafe Kopi Instan', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-027', 'items_name' => 'House of Toraja', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-028', 'items_name' => 'House of Mandheling', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-029', 'items_name' => 'House of Sumatra', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-030', 'items_name' => 'Java Instant Coffee', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-031', 'items_name' => 'Blue Mountain Kopi Instant', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-032', 'items_name' => 'Salute Instant Coffee', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-033', 'items_name' => 'MCoffee Instant Coffee', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-034', 'items_name' => 'Coffee Plus Instant Coffee', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-035', 'items_name' => 'Maxcafe Instant Coffee', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-036', 'items_name' => 'Indocafe Latte Mandheling Origins', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-037', 'items_name' => 'Indocafe Latte Gayo Origins', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-038', 'items_name' => 'Indocafe The Java Drip Coffee Pouch', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-039', 'items_name' => 'Indocafe The Bali Drip Coffee Pouch', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-040', 'items_name' => 'Indocafe The Sumatra Mandheling Drip Coffee Pouch', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-041', 'items_name' => 'Indocafe Sumatra', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-042', 'items_name' => 'Indocafe Sumatra Gold', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-043', 'items_name' => 'Indocafe The Flores Komodo Island Drip Coffee Pouch', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-044', 'items_name' => 'Indocafe The Sumatra Civet Drip Coffee Pouch', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-045', 'items_name' => 'Indocafe The Sumatra Organic Drip Coffee Pouch', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-046', 'items_name' => 'MaxTea Tarikk', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-047', 'items_name' => 'MaxTea Tarikk + Jahe', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-048', 'items_name' => 'MaxTea Lemon Tea', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-049', 'items_name' => 'MaxTea Apple Tea', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-050', 'items_name' => 'MaxTea Peach Tea', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-051', 'items_name' => 'TEAPLUS', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-052', 'items_name' => 'Salute Tea', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-053', 'items_name' => 'Airport Teh Instan', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-054', 'items_name' => 'MaxTea Milk Tea', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-055', 'items_name' => 'MaxTea Matcha Latte', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-056', 'items_name' => 'INDO CREAMER', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-057', 'items_name' => 'MaxCreamer', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-058', 'items_name' => 'Blue Mountain Creamer', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-059', 'items_name' => 'Indocafe Ginseng Cereal', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-060', 'items_name' => 'Chococino', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-061', 'items_name' => 'Chococino Light', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-062', 'items_name' => 'Koko Kopi Coklat', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-063', 'items_name' => 'Java Cocoa', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-064', 'items_name' => 'IndoKoko Chocolate', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
            ['items_code' => 'INC-065', 'items_name' => 'IndoSereal Chocolate', 'items_category_id' => 3, 'items_group_id' => 4, 'unit_id' => 1, 'buy_price' => 1000.00, 'sell_price' => 1500.00, 'stock' => 0, 'items_description' => 'Produk Incofood'],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
