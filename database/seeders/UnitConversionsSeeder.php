<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitConversionsSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ PERBAIKAN: Ambil item_id yang benar-benar ada
        $items = DB::table('items')->pluck('id')->toArray();

        // ✅ Cek jika tidak ada items
        if (empty($items)) {
            $this->command->warn('No items found in database. Skipping unit conversions seeder.');
            return;
        }

        // ✅ Cek jika units tidak lengkap
        $unitCount = DB::table('units')->count();
        if ($unitCount < 4) {
            $this->command->warn('Not enough units in database. Expected 4 units (pcs, pack, karton, container).');
            return;
        }

        $conversions = [];

        // ✅ Loop hanya untuk item yang benar-benar ada
        foreach ($items as $itemId) {
            // Unit 1 = pcs (base unit)
            // Unit 2 = pack  
            // Unit 3 = karton
            // Unit 4 = container

            // 1 pack = 10 pcs
            $conversions[] = [
                'item_id' => $itemId,
                'from_unit_id' => 2, // pack
                'to_unit_id' => 1,   // pcs (base)
                'conversion_value' => 10,
                'description' => '1 pack = 10 pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 1 karton = 50 pcs
            $conversions[] = [
                'item_id' => $itemId,
                'from_unit_id' => 3, // karton
                'to_unit_id' => 1,   // pcs (base)
                'conversion_value' => 50,
                'description' => '1 karton = 50 pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 1 container = 1000 pcs
            $conversions[] = [
                'item_id' => $itemId,
                'from_unit_id' => 4, // container
                'to_unit_id' => 1,   // pcs (base)
                'conversion_value' => 1000,
                'description' => '1 container = 1000 pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ✅ Insert dengan error handling
        try {
            $chunks = array_chunk($conversions, 100);
            foreach ($chunks as $chunk) {
                DB::table('unit_conversions')->insert($chunk);
            }

            $this->command->info('Unit conversions seeded successfully for ' . count($items) . ' items.');
        } catch (\Exception $e) {
            $this->command->error('Error seeding unit conversions: ' . $e->getMessage());
        }
    }
}
/**
 * Run the database seeds.
 */
    // public function run(): void
    // {
    //     $items = DB::table('items')->get();
    //     $conversions = [];

    //     foreach ($items as $item) {
    //         // Tentukan konversi berdasarkan jenis produk
    //         if (str_starts_with($item->items_code, 'STTC')) {
    //             // ROKOK: PCS -> PACK -> BOX
    //             $this->addRokokConversions($item->id, $conversions);
    //         } elseif (str_starts_with($item->items_code, 'INC')) {
    //             // KOPI/MINUMAN: PCS -> PACK -> BOX
    //             $this->addMinumanConversions($item->id, $conversions);
    //         }
    //     }

    //     // Insert data dalam batch
    //     if (!empty($conversions)) {
    //         $chunks = array_chunk($conversions, 100);
    //         foreach ($chunks as $chunk) {
    //             DB::table('unit_conversions')->insert($chunk);
    //         }
    //     }
    // }

    // private function addRokokConversions($itemId, &$conversions)
    // {
    //     // ROKOK: 1 PCS = base unit (tidak perlu konversi)
    //     // 1 PACK = 20 PCS (1 bungkus rokok = 20 batang)
    //     $conversions[] = [
    //         'item_id' => $itemId,
    //         'from_unit_id' => 2, // PACK
    //         'to_unit_id' => 1,   // PCS (base)
    //         'conversion_value' => 20,
    //         'description' => '1 Pack = 20 Pieces',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];

    //     // 1 BOX = 200 PCS (1 box = 10 pack x 20 pcs)
    //     $conversions[] = [
    //         'item_id' => $itemId,
    //         'from_unit_id' => 3, // BOX
    //         'to_unit_id' => 1,   // PCS (base)
    //         'conversion_value' => 200,
    //         'description' => '1 Box = 200 Pieces',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];
    // }

    // private function addMinumanConversions($itemId, &$conversions)
    // {
    //     // KOPI/MINUMAN: 1 PCS = base unit (1 sachet)
    //     // 1 PACK = 10 PCS (1 pack = 10 sachet)
    //     $conversions[] = [
    //         'item_id' => $itemId,
    //         'from_unit_id' => 2, // PACK
    //         'to_unit_id' => 1,   // PCS (base)
    //         'conversion_value' => 10,
    //         'description' => '1 Pack = 10 Sachets',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];

    //     // 1 BOX = 120 PCS (1 box = 12 pack x 10 sachet)
    //     $conversions[] = [
    //         'item_id' => $itemId,
    //         'from_unit_id' => 3, // BOX
    //         'to_unit_id' => 1,   // PCS (base)
    //         'conversion_value' => 120,
    //         'description' => '1 Box = 120 Sachets',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];
    // }