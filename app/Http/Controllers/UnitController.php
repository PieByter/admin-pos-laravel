<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Unit;
use App\Models\Item;
use App\Models\UnitConversion;
use App\Models\ActivityLog;

class UnitController extends Controller
{
    // private function canRead(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('satuan', $permissions) || in_array('satuan_read', $permissions);
    // }

    // private function canWrite(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('satuan', $permissions);
    // }

    // private function requireReadAccess(): void
    // {
    //     if (!$this->canRead()) {
    //         abort(404, 'Access Denied');
    //     }
    // }

    // private function requireWriteAccess(): void
    // {
    //     if (!$this->canWrite()) {
    //         abort(404, 'Access Denied');
    //     }
    // }

    // private function getPermissionData(): array
    // {
    //     return [
    //         'can_read' => $this->canRead(),
    //         'can_write' => $this->canWrite()
    //     ];
    // }

    // public function __construct()
    // {
    //     $this->middleware('permission:satuan_view')->only(['index', 'show']);
    //     $this->middleware('permission:satuan_create')->only(['create', 'store']);
    //     $this->middleware('permission:satuan_update')->only(['edit', 'update']);
    //     $this->middleware('permission:satuan_delete')->only(['destroy']);
    // }

    // Tampilkan daftar unit
    public function index(Request $request)
    {

        $search = $request->get('search');
        $query = Unit::query();

        if ($search) {
            $query->where('unit_name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $units = $query->orderBy('id', 'ASC')->get();

        $data = array_merge([
            'units' => $units,
            'search' => $search,
            'title' => 'Daftar Satuan'
        ]);

        return view('units.index', $data);
    }

    // Tampilkan form tambah unit
    public function create()
    {

        return view('units.create', [
            'title' => 'Tambah Satuan'
        ]);
    }

    // Simpan unit baru
    public function store(Request $request)
    {

        $request->validate([
            'unit_name' => 'required|string|max:50|unique:units,unit_name',
            'description' => 'nullable|string|max:100'
        ], [
            'unit_name.required' => 'Nama satuan wajib diisi',
            'unit_name.max' => 'Nama satuan maksimal 50 karakter',
            'unit_name.unique' => 'Nama satuan sudah ada',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        try {
            DB::beginTransaction();

            Unit::create([
                'unit_name' => $request->unit_name,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah satuan: ' . $request->unit_name,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('units.index')
                ->with('success', 'Satuan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating unit: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan satuan: ' . $e->getMessage());
        }
    }

    // Tampilkan detail unit
    public function show($id)
    {

        $unit = Unit::find($id);
        if (!$unit) {
            return redirect()->route('units.index')
                ->with('error', 'Satuan tidak ditemukan');
        }

        // Get items using this unit - menggunakan relasi
        $items = $unit->items;

        // Get unit conversions - menggunakan relasi
        $conversionsFrom = $unit->unitConversionsFrom()->with('toUnit')->get();
        $conversionsTo = $unit->unitConversionsTo()->with('fromUnit')->get();
        $conversions = $conversionsFrom->merge($conversionsTo);

        return view('units.show', [
            'unit' => $unit,
            'items' => $items,
            'conversions' => $conversions,
            'title' => 'Detail Satuan'
        ]);
    }

    // Tampilkan form edit unit
    public function edit($id)
    {

        $unit = Unit::findOrFail($id);

        return view('units.edit', [
            'unit' => $unit,
            'title' => 'Edit Satuan'
        ]);
    }

    // Update unit
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'unit_name' => 'required|string|max:50|unique:units,unit_name,' . $id,
            'description' => 'nullable|string|max:100'
        ], [
            'unit_name.required' => 'Nama satuan wajib diisi',
            'unit_name.max' => 'Nama satuan maksimal 50 karakter',
            'unit_name.unique' => 'Nama satuan sudah ada',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        try {
            DB::beginTransaction();

            $unit->update([
                'unit_name' => $request->unit_name,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate satuan: ' . $request->unit_name,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('units.index')
                ->with('success', 'Satuan berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating unit: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate satuan: ' . $e->getMessage());
        }
    }

    // Hapus unit
    public function destroy($id)
    {

        $unit = Unit::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if unit is being used in items - menggunakan relasi
            // $itemCount = $unit->items()->count();
            // if ($itemCount > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan pada ' . $itemCount . ' barang!');
            // }

            // // Check if unit is being used in unit conversions - menggunakan relasi
            // $conversionFromCount = $unit->unitConversionsFrom()->count();
            // $conversionToCount = $unit->unitConversionsTo()->count();
            // if ($conversionFromCount > 0 || $conversionToCount > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan dalam konversi satuan!');
            // }

            // // Check if unit is being used in sales order items - menggunakan relasi
            // $salesOrderItemCount = $unit->salesOrderItems()->count();
            // if ($salesOrderItemCount > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan dalam penjualan!');
            // }

            // // Check if unit is being used in purchase order items - menggunakan relasi
            // $purchaseOrderItemCount = $unit->purchaseOrderItems()->count();
            // if ($purchaseOrderItemCount > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan dalam pembelian!');
            // }

            $unitName = $unit->unit_name;
            $unit->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus satuan: ' . $unitName,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('units.index')
                ->with('success', 'Satuan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting unit: ' . $e->getMessage());

            return redirect()->route('units.index')
                ->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
        }
    }

    // AJAX method untuk mencari unit
    public function search(Request $request)
    {

        $query = $request->get('q', '');
        $units = Unit::where('unit_name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'unit_name', 'description']);

        return response()->json($units);
    }

    // Method untuk export data unit
    public function export()
    {

        $units = Unit::orderBy('unit_name', 'ASC')->get();

        $csv = "Nama Satuan,Keterangan\n";
        foreach ($units as $unit) {
            $csv .= '"' . $unit->unit_name . '",';
            $csv .= '"' . ($unit->description ?? '') . '"';
            $csv .= "\n";
        }

        $fileName = 'units_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    // Method untuk import data unit
    public function import(Request $request)
    {

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
                if (count($row) < 1) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                    continue;
                }

                try {
                    // Check if unit name already exists - menggunakan Eloquent
                    $existing = Unit::where('unit_name', $row[0])->exists();
                    if ($existing) {
                        $errors[] = "Baris " . ($index + 2) . ": Satuan '{$row[0]}' sudah ada";
                        continue;
                    }

                    Unit::create([
                        'unit_name' => $row[0],
                        'description' => $row[1] ?? null
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
                    'activity' => 'Import satuan: ' . $imported . ' data berhasil diimport',
                    'created_at' => now()
                ]);

                return redirect()->route('units.index')
                    ->with('success', "Berhasil mengimport {$imported} satuan");
            } else {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'Import gagal: ' . implode(', ', array_slice($errors, 0, 5)));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error importing units: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    // Method untuk get unit conversions by item
    public function getConversionsByItem($itemId)
    {

        // Menggunakan model UnitConversion dengan relasi
        $conversions = UnitConversion::with(['unit', 'item'])
            ->where('item_id', $itemId)
            ->get();

        return response()->json($conversions);
    }

    // Method untuk AJAX save unit
    public function ajaxSave(Request $request)
    {

        $request->validate([
            'unit_name' => 'required|string|max:50|unique:units,unit_name',
            'description' => 'nullable|string|max:100'
        ]);

        try {
            $unit = Unit::create([
                'unit_name' => $request->unit_name,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah satuan via AJAX: ' . $request->unit_name,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil ditambahkan',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating unit via AJAX: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan satuan: ' . $e->getMessage()
            ], 500);
        }
    }
}