<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitConversionsSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua item id
        $items = DB::table('items')->pluck('id')->toArray();

        // Ambil semua unit, pastikan ada 'pcs' sebagai base unit (id = 1)
        $units = DB::table('units')->pluck('unit_name', 'id')->toArray();

        // Cari id base unit (pcs)
        $baseUnitId = array_search('pcs', array_map('strtolower', $units));
        if (!$baseUnitId) {
            $this->command->error('Base unit "pcs" tidak ditemukan di tabel units!');
            return;
        }

        // Mapping satuan lain ke nilai konversi ke pcs
        $conversionMap = [
            'pack'      => 10,
            'karton'    => 50,
            'container' => 1000,
        ];

        $conversions = [];

        foreach ($items as $itemId) {
            foreach ($conversionMap as $unitName => $value) {
                // Cari id unit (selain pcs)
                $unitId = array_search($unitName, array_map('strtolower', $units));
                if ($unitId && $unitId != $baseUnitId) {
                    $conversions[] = [
                        'item_id'         => $itemId,
                        'unit_id'         => $unitId,
                        'conversion_value' => $value,
                        'description'     => "1 $unitName = $value pcs",
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }
        }

        if (empty($conversions)) {
            $this->command->warn('Tidak ada data konversi yang diinsert.');
            return;
        }

        // Insert batch
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