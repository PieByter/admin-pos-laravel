<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['unit_name' => 'pcs', 'description' => 'Individual pieces (base unit)'],
            ['unit_name' => 'pack', 'description' => 'Contains 10 pieces'],
            ['unit_name' => 'karton', 'description' => 'Contains 50 pieces'],
            ['unit_name' => 'container', 'description' => 'Large container unit (custom use)'],
            // ['unit_name' => 'Kilogram', 'description' => 'Weight in kilograms'],
            // ['unit_name' => 'Gram', 'description' => 'Weight in grams'],
            // ['unit_name' => 'Liter', 'description' => 'Volume in liters'],
            // ['unit_name' => 'Milliliter', 'description' => 'Volume in milliliters'],
            // ['unit_name' => 'Meter', 'description' => 'Length in meters'],
            // ['unit_name' => 'Centimeter', 'description' => 'Length in centimeters'],
            // ['unit_name' => 'Box', 'description' => 'Items sold in boxes'],
            // ['unit_name' => 'Pack', 'description' => 'Items sold in packs'],
            // ['unit_name' => 'Dozen', 'description' => 'Set of 12 items'],
            // ['unit_name' => 'Pair', 'description' => 'Set of 2 items'],
            // ['unit_name' => 'Set', 'description' => 'Complete set of items'],
            // ['unit_name' => 'Bundle', 'description' => 'Items sold in bundles'],
            // ['unit_name' => 'Carton', 'description' => 'Items sold in cartons'],
            // ['unit_name' => 'Case', 'description' => 'Items sold in cases'],
            // ['unit_name' => 'Roll', 'description' => 'Items sold in rolls'],
            // ['unit_name' => 'Sheet', 'description' => 'Items sold in sheets'],
            // ['unit_name' => 'Bottle', 'description' => 'Items sold in bottles'],
            // ['unit_name' => 'Can', 'description' => 'Items sold in cans'],
            // ['unit_name' => 'Tube', 'description' => 'Items sold in tubes'],
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert(array_merge($unit, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
