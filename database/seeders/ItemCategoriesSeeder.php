<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Smartphones',
            'Laptops',
            'Tablets',
            'Headphones',
            'Cameras',
            'Televisions',
            'Gaming Consoles',
            'Smart Watches',
            'Speakers',
            'Computer Accessories',
            'Men\'s Clothing',
            'Women\'s Clothing',
            'Children\'s Clothing',
            'Shoes',
            'Bags',
            'Snacks',
            'Beverages',
            'Frozen Foods',
            'Fresh Produce',
            'Dairy Products',
        ];

        foreach ($categories as $category) {
            DB::table('item_categories')->insert([
                'category_name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
