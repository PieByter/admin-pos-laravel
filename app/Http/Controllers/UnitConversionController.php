<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UnitConversion;
use App\Models\Item;
use App\Models\Unit;
use App\Models\ActivityLog;

class UnitConversionController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('satuan_konversi', $permissions) || in_array('satuan_konversi_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('satuan_konversi', $permissions);
    }

    private function requireReadAccess(): void
    {
        if (!$this->canRead()) {
            abort(404, 'Access Denied');
        }
    }

    private function requireWriteAccess(): void
    {
        if (!$this->canWrite()) {
            abort(404, 'Access Denied');
        }
    }

    private function getPermissionData(): array
    {
        return [
            'can_read' => $this->canRead(),
            'can_write' => $this->canWrite()
        ];
    }

    // Tampilkan daftar unit conversion
    public function index(Request $request)
    {
        $this->requireReadAccess();

        $search = $request->get('search');
        $itemId = $request->get('item_id');

        $query = DB::table('unit_conversions')
            ->leftJoin('items', 'items.id', '=', 'unit_conversions.item_id')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select(
                'unit_conversions.*',
                'items.name as item_name',
                'units.name as unit_name'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.name', 'LIKE', "%{$search}%")
                    ->orWhere('units.name', 'LIKE', "%{$search}%")
                    ->orWhere('unit_conversions.conversion_rate', 'LIKE', "%{$search}%")
                    ->orWhere('unit_conversions.description', 'LIKE', "%{$search}%");
            });
        }

        if ($itemId) {
            $query->where('unit_conversions.item_id', $itemId);
        }

        $conversions = $query->orderBy('unit_conversions.item_id', 'ASC')
            ->orderBy('unit_conversions.unit_id', 'ASC')
            ->get();

        $items = Item::orderBy('name')->get();

        $data = array_merge($this->getPermissionData(), [
            'conversions' => $conversions,
            'items' => $items,
            'search' => $search,
            'selected_item_id' => $itemId,
            'title' => 'Daftar Satuan Konversi'
        ]);

        return view('unit_conversions.index', $data);
    }

    // Tampilkan form tambah unit conversion
    public function create()
    {
        $this->requireWriteAccess();

        $items = Item::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('unit_conversions.create', [
            'items' => $items,
            'units' => $units,
            'title' => 'Tambah Satuan Konversi'
        ]);
    }

    // Simpan unit conversion baru
    public function store(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'unit_id' => 'required|exists:units,id',
            'conversion_rate' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ], [
            'item_id.required' => 'Barang wajib dipilih',
            'item_id.exists' => 'Barang tidak valid',
            'unit_id.required' => 'Satuan wajib dipilih',
            'unit_id.exists' => 'Satuan tidak valid',
            'conversion_rate.required' => 'Nilai konversi wajib diisi',
            'conversion_rate.numeric' => 'Nilai konversi harus berupa angka',
            'conversion_rate.min' => 'Nilai konversi minimal 0.01',
            'description.max' => 'Keterangan maksimal 255 karakter'
        ]);

        try {
            DB::beginTransaction();

            // Check if combination already exists
            $existing = UnitConversion::where('item_id', $request->item_id)
                ->where('unit_id', $request->unit_id)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kombinasi barang dan satuan sudah ada!');
            }

            $conversion = UnitConversion::create([
                'item_id' => $request->item_id,
                'unit_id' => $request->unit_id,
                'conversion_rate' => $request->conversion_rate,
                'description' => $request->description
            ]);

            // Get item and unit names for logging
            $item = Item::find($request->item_id);
            $unit = Unit::find($request->unit_id);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah satuan konversi: ' .
                    ($item ? $item->name : $request->item_id) . ' - ' .
                    ($unit ? $unit->name : $request->unit_id),
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('unit-conversions.index')
                ->with('success', 'Konversi berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating unit conversion: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan konversi: ' . $e->getMessage());
        }
    }

    // Tampilkan detail unit conversion
    public function show($id)
    {
        $this->requireReadAccess();

        $conversion = DB::table('unit_conversions')
            ->leftJoin('items', 'items.id', '=', 'unit_conversions.item_id')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select(
                'unit_conversions.*',
                'items.name as item_name',
                'items.code as item_code',
                'units.name as unit_name'
            )
            ->where('unit_conversions.id', $id)
            ->first();

        if (!$conversion) {
            return redirect()->route('unit-conversions.index')
                ->with('error', 'Konversi satuan tidak ditemukan');
        }

        return view('unit_conversions.show', [
            'conversion' => $conversion,
            'can_write' => $this->canWrite(),
            'title' => 'Detail Satuan Konversi'
        ]);
    }

    // Tampilkan form edit unit conversion
    public function edit($id)
    {
        $this->requireWriteAccess();

        $conversion = DB::table('unit_conversions')
            ->leftJoin('items', 'items.id', '=', 'unit_conversions.item_id')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select(
                'unit_conversions.*',
                'items.name as item_name',
                'units.name as unit_name'
            )
            ->where('unit_conversions.id', $id)
            ->first();

        if (!$conversion) {
            return redirect()->route('unit-conversions.index')
                ->with('error', 'Konversi satuan tidak ditemukan');
        }

        $items = Item::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('unit_conversions.edit', [
            'conversion' => $conversion,
            'items' => $items,
            'units' => $units,
            'title' => 'Edit Satuan Konversi'
        ]);
    }

    // Update unit conversion
    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

        $conversion = UnitConversion::find($id);
        if (!$conversion) {
            return redirect()->route('unit-conversions.index')
                ->with('error', 'Konversi satuan tidak ditemukan');
        }

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'unit_id' => 'required|exists:units,id',
            'conversion_rate' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ], [
            'item_id.required' => 'Barang wajib dipilih',
            'item_id.exists' => 'Barang tidak valid',
            'unit_id.required' => 'Satuan wajib dipilih',
            'unit_id.exists' => 'Satuan tidak valid',
            'conversion_rate.required' => 'Nilai konversi wajib diisi',
            'conversion_rate.numeric' => 'Nilai konversi harus berupa angka',
            'conversion_rate.min' => 'Nilai konversi minimal 0.01',
            'description.max' => 'Keterangan maksimal 255 karakter'
        ]);

        try {
            DB::beginTransaction();

            // Check if combination already exists (excluding current record)
            $existing = UnitConversion::where('item_id', $request->item_id)
                ->where('unit_id', $request->unit_id)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kombinasi barang dan satuan sudah ada!');
            }

            $conversion->update([
                'item_id' => $request->item_id,
                'unit_id' => $request->unit_id,
                'conversion_rate' => $request->conversion_rate,
                'description' => $request->description
            ]);

            // Get item and unit names for logging
            $item = Item::find($request->item_id);
            $unit = Unit::find($request->unit_id);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengubah satuan konversi: ' .
                    ($item ? $item->name : $request->item_id) . ' - ' .
                    ($unit ? $unit->name : $request->unit_id),
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('unit-conversions.index')
                ->with('success', 'Konversi berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating unit conversion: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate konversi: ' . $e->getMessage());
        }
    }

    // Hapus unit conversion
    public function destroy($id)
    {
        $this->requireWriteAccess();

        $conversion = UnitConversion::find($id);
        if (!$conversion) {
            return redirect()->route('unit-conversions.index')
                ->with('error', 'Konversi satuan tidak ditemukan');
        }

        try {
            DB::beginTransaction();

            // Check if conversion is being used in purchase details
            $purchaseDetailCount = DB::table('purchase_details')
                ->where('item_id', $conversion->item_id)
                ->where('unit_id', $conversion->unit_id)
                ->count();

            if ($purchaseDetailCount > 0) {
                return redirect()->back()
                    ->with('error', 'Konversi tidak bisa dihapus karena masih digunakan dalam transaksi pembelian!');
            }

            // Check if conversion is being used in sale details
            $saleDetailCount = DB::table('sale_details')
                ->where('item_id', $conversion->item_id)
                ->where('unit_id', $conversion->unit_id)
                ->count();

            if ($saleDetailCount > 0) {
                return redirect()->back()
                    ->with('error', 'Konversi tidak bisa dihapus karena masih digunakan dalam transaksi penjualan!');
            }

            // Get item and unit names for logging
            $item = Item::find($conversion->item_id);
            $unit = Unit::find($conversion->unit_id);

            $conversion->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus satuan konversi: ' .
                    ($item ? $item->name : $conversion->item_id) . ' - ' .
                    ($unit ? $unit->name : $conversion->unit_id),
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('unit-conversions.index')
                ->with('success', 'Konversi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting unit conversion: ' . $e->getMessage());

            return redirect()->route('unit-conversions.index')
                ->with('error', 'Gagal menghapus konversi: ' . $e->getMessage());
        }
    }

    // AJAX method untuk get conversions by item
    public function getByItem($itemId)
    {
        $this->requireReadAccess();

        $conversions = DB::table('unit_conversions')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select('unit_conversions.*', 'units.name as unit_name')
            ->where('unit_conversions.item_id', $itemId)
            ->get();

        return response()->json($conversions);
    }

    // AJAX method untuk search conversions
    public function search(Request $request)
    {
        $this->requireReadAccess();

        $search = $request->get('q', '');
        $itemId = $request->get('item_id');

        $query = DB::table('unit_conversions')
            ->leftJoin('items', 'items.id', '=', 'unit_conversions.item_id')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select(
                'unit_conversions.*',
                'items.name as item_name',
                'units.name as unit_name'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.name', 'LIKE', "%{$search}%")
                    ->orWhere('units.name', 'LIKE', "%{$search}%");
            });
        }

        if ($itemId) {
            $query->where('unit_conversions.item_id', $itemId);
        }

        $conversions = $query->limit(10)->get();

        return response()->json($conversions);
    }

    // Method untuk bulk import conversions
    public function import(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data);

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                if (count($row) < 3) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                    continue;
                }

                try {
                    // Find item by name
                    $item = Item::where('name', $row[0])->first();
                    if (!$item) {
                        $errors[] = "Baris " . ($index + 2) . ": Barang '{$row[0]}' tidak ditemukan";
                        continue;
                    }

                    // Find unit by name
                    $unit = Unit::where('name', $row[1])->first();
                    if (!$unit) {
                        $errors[] = "Baris " . ($index + 2) . ": Satuan '{$row[1]}' tidak ditemukan";
                        continue;
                    }

                    // Check if combination already exists
                    $existing = UnitConversion::where('item_id', $item->id)
                        ->where('unit_id', $unit->id)
                        ->first();
                    if ($existing) {
                        $errors[] = "Baris " . ($index + 2) . ": Kombinasi '{$row[0]}' - '{$row[1]}' sudah ada";
                        continue;
                    }

                    UnitConversion::create([
                        'item_id' => $item->id,
                        'unit_id' => $unit->id,
                        'conversion_rate' => (float)$row[2],
                        'description' => $row[3] ?? null
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if (empty($errors)) {
                DB::commit();

                // Log activity
                ActivityLog::create([
                    'user_id' => session('user_id'),
                    'activity' => 'Import satuan konversi: ' . $imported . ' data berhasil diimport',
                    'created_at' => now()
                ]);

                return redirect()->route('unit-conversions.index')
                    ->with('success', "Berhasil mengimport {$imported} konversi satuan");
            } else {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'Import gagal: ' . implode(', ', array_slice($errors, 0, 5)));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error importing unit conversions: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    // Method untuk export conversions
    public function export()
    {
        $this->requireReadAccess();

        $conversions = DB::table('unit_conversions')
            ->leftJoin('items', 'items.id', '=', 'unit_conversions.item_id')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select(
                'items.name as item_name',
                'units.name as unit_name',
                'unit_conversions.conversion_rate',
                'unit_conversions.description'
            )
            ->orderBy('items.name')
            ->orderBy('units.name')
            ->get();

        $csv = "Nama Barang,Satuan,Nilai Konversi,Keterangan\n";
        foreach ($conversions as $conversion) {
            $csv .= '"' . $conversion->item_name . '",';
            $csv .= '"' . $conversion->unit_name . '",';
            $csv .= '"' . $conversion->conversion_rate . '",';
            $csv .= '"' . ($conversion->description ?? '') . '"';
            $csv .= "\n";
        }

        $filename = 'unit_conversions_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}