<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Unit;
use App\Models\ActivityLog;

class UnitController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('satuan', $permissions) || in_array('satuan_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('satuan', $permissions);
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

    // Tampilkan daftar unit
    public function index(Request $request)
    {
        $this->requireReadAccess();

        $search = $request->get('search');
        $query = Unit::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $units = $query->orderBy('name', 'ASC')->get();

        $data = array_merge($this->getPermissionData(), [
            'units' => $units,
            'search' => $search,
            'title' => 'Daftar Satuan'
        ]);

        return view('units.index', $data);
    }

    // Tampilkan form tambah unit
    public function create()
    {
        $this->requireWriteAccess();

        return view('units.create', [
            'title' => 'Tambah Satuan'
        ]);
    }

    // Simpan unit baru
    public function store(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'name' => 'required|string|max:50|unique:units,name',
            'description' => 'nullable|string|max:100'
        ], [
            'name.required' => 'Nama satuan wajib diisi',
            'name.max' => 'Nama satuan maksimal 50 karakter',
            'name.unique' => 'Nama satuan sudah ada',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        try {
            DB::beginTransaction();

            $unit = Unit::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah satuan: ' . $request->name,
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
        $this->requireReadAccess();

        $unit = Unit::find($id);
        if (!$unit) {
            return redirect()->route('units.index')
                ->with('error', 'Satuan tidak ditemukan');
        }

        // Get items using this unit
        $items = DB::table('items')
            ->where('unit_id', $id)
            ->get();

        // Get unit conversions
        $conversions = DB::table('unit_conversions')
            ->leftJoin('items', 'items.id', '=', 'unit_conversions.item_id')
            ->select('unit_conversions.*', 'items.name as item_name')
            ->where('unit_conversions.unit_id', $id)
            ->get();

        return view('units.show', [
            'unit' => $unit,
            'items' => $items,
            'conversions' => $conversions,
            'can_write' => $this->canWrite(),
            'title' => 'Detail Satuan'
        ]);
    }

    // Tampilkan form edit unit
    public function edit($id)
    {
        $this->requireWriteAccess();

        $unit = Unit::find($id);
        if (!$unit) {
            return redirect()->route('units.index')
                ->with('error', 'Satuan tidak ditemukan!');
        }

        return view('units.edit', [
            'unit' => $unit,
            'title' => 'Edit Satuan'
        ]);
    }

    // Update unit
    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

        $unit = Unit::find($id);
        if (!$unit) {
            return redirect()->route('units.index')
                ->with('error', 'Satuan tidak ditemukan!');
        }

        $request->validate([
            'name' => 'required|string|max:50|unique:units,name,' . $id,
            'description' => 'nullable|string|max:100'
        ], [
            'name.required' => 'Nama satuan wajib diisi',
            'name.max' => 'Nama satuan maksimal 50 karakter',
            'name.unique' => 'Nama satuan sudah ada',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        try {
            DB::beginTransaction();

            $unit->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate satuan: ' . $request->name,
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
        $this->requireWriteAccess();

        $unit = Unit::find($id);
        if (!$unit) {
            return redirect()->route('units.index')
                ->with('error', 'Satuan tidak ditemukan!');
        }

        try {
            DB::beginTransaction();

            // Check if unit is being used in items
            $itemCount = DB::table('items')->where('unit_id', $id)->count();
            if ($itemCount > 0) {
                return redirect()->back()
                    ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan pada barang!');
            }

            // Check if unit is being used in unit conversions
            $conversionCount = DB::table('unit_conversions')->where('unit_id', $id)->count();
            if ($conversionCount > 0) {
                return redirect()->back()
                    ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan dalam konversi satuan!');
            }

            // Check if unit is being used in purchase details
            $purchaseDetailCount = DB::table('purchase_details')->where('unit_id', $id)->count();
            if ($purchaseDetailCount > 0) {
                return redirect()->back()
                    ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan dalam detail pembelian!');
            }

            // Check if unit is being used in sale details
            $saleDetailCount = DB::table('sale_details')->where('unit_id', $id)->count();
            if ($saleDetailCount > 0) {
                return redirect()->back()
                    ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan dalam detail penjualan!');
            }

            $unitName = $unit->name;
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
        $this->requireReadAccess();

        $query = $request->get('q', '');
        $units = Unit::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'description']);

        return response()->json($units);
    }

    // Method untuk export data unit
    public function export()
    {
        $this->requireReadAccess();

        $units = Unit::orderBy('name', 'ASC')->get();

        $csv = "Nama Satuan,Keterangan\n";
        foreach ($units as $unit) {
            $csv .= '"' . $unit->name . '",';
            $csv .= '"' . ($unit->description ?? '') . '"';
            $csv .= "\n";
        }

        $filename = 'units_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    // Method untuk import data unit
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
                if (count($row) < 1) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                    continue;
                }

                try {
                    // Check if unit name already exists
                    $existing = Unit::where('name', $row[0])->first();
                    if ($existing) {
                        $errors[] = "Baris " . ($index + 2) . ": Satuan '{$row[0]}' sudah ada";
                        continue;
                    }

                    Unit::create([
                        'name' => $row[0],
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
        $this->requireReadAccess();

        $conversions = DB::table('unit_conversions')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select('unit_conversions.*', 'units.name as unit_name')
            ->where('unit_conversions.item_id', $itemId)
            ->get();

        return response()->json($conversions);
    }
}