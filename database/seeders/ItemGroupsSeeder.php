<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'Electronics',
            'Clothing',
            'Food & Beverages',
            'Home & Garden',
            'Sports & Outdoors',
            'Books & Media',
            'Health & Beauty',
            'Automotive',
            'Office Supplies',
            'Toys & Games',
            'Jewelry & Accessories',
            'Pet Supplies',
            'Musical Instruments',
            'Hardware & Tools',
            'Baby & Kids',
            'Arts & Crafts',
            'Industrial Equipment',
            'Travel & Luggage',
            'Furniture',
            'Kitchen & Dining',
        ];

        foreach ($groups as $group) {
            DB::table('item_groups')->insert([
                'group_name' => $group,
                'description' => 'Kategori grup barang: ' . $group,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
