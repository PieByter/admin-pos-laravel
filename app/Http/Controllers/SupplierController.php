<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Supplier;
use App\Models\ActivityLog;

class SupplierController extends Controller
{
    // private function canRead(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('supplier', $permissions) || in_array('supplier_read', $permissions);
    // }

    // private function canWrite(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('supplier', $permissions);
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

    // Tampilkan daftar supplier
    public function index()
    {

        $suppliers = Supplier::orderBy('name', 'ASC')->get();

        $data =  [
            'title' => 'Manajemen Supplier',
            'suppliers' => $suppliers
        ];

        return view('suppliers.index', $data);
    }

    // Tampilkan detail supplier
    public function show($id)
    {

        $supplier = Supplier::find($id);
        if (!$supplier) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Supplier',
            'supplier' => $supplier,
        ];

        return view('suppliers.show', $data);
    }

    // Tampilkan form tambah supplier
    public function create()
    {

        $data = [
            'title' => 'Tambah Supplier Baru'
        ];

        return view('suppliers.create', $data);
    }

    // Simpan supplier baru
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Nama supplier wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'phone_number.required' => 'No telepon wajib diisi',
            'contact_email.required' => 'Email wajib diisi',
            'contact_email.email' => 'Format email tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive'
        ]);

        try {
            DB::beginTransaction();

            Supplier::create([
                'name' => $request->name,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'contact_email' => $request->contact_email,
                'status' => $request->status,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah supplier: ' . $request->name,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating supplier: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }

    // Tampilkan form edit supplier
    public function edit($id)
    {

        $supplier = Supplier::find($id);
        if (!$supplier) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Data Supplier',
            'supplier' => $supplier
        ];

        return view('suppliers.edit', $data);
    }

    // Update supplier
    public function update(Request $request, $id)
    {

        $supplier = Supplier::find($id);
        if (!$supplier) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak ditemukan');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Nama supplier wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'phone_number.required' => 'No telepon wajib diisi',
            'contact_email.required' => 'Email wajib diisi',
            'contact_email.email' => 'Format email tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive'
        ]);
        try {
            DB::beginTransaction();

            $supplier->update([
                'name' => $request->name,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'contact_email' => $request->contact_email,
                'status' => $request->status,
                'description' => $request->description
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate supplier: ' . $request->name,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating supplier: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate supplier: ' . $e->getMessage());
        }
    }

    // Hapus supplier
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak ditemukan');
        }

        try {
            DB::beginTransaction();

            // Check if supplier is being used in purchases
            // $purchaseCount = DB::table('purchases')
            //     ->where('supplier_id', $id)
            //     ->count();

            // if ($purchaseCount > 0) {
            //     return redirect()->route('suppliers.index')
            //         ->with('error', 'Supplier tidak dapat dihapus karena masih digunakan dalam transaksi pembelian');
            // }

            $supplierName = $supplier->name;
            $supplier->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus supplier: ' . $supplierName,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting supplier: ' . $e->getMessage());

            return redirect()->route('suppliers.index')
                ->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }

    // AJAX method untuk mencari supplier
    public function search(Request $request)
    {

        $query = $request->get('q', '');
        $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->where('status', 'active')
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($suppliers);
    }

    // Method untuk toggle status
    public function toggleStatus($id)
    {

        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan'
            ], 404);
        }

        try {
            $newStatus = $supplier->status === 'active' ? 'inactive' : 'active';
            $supplier->update(['status' => $newStatus]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengubah status supplier: ' . $supplier->name . ' menjadi ' . $newStatus,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status supplier berhasil diubah',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling supplier status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status supplier'
            ], 500);
        }
    }

    // Method untuk export data supplier
    public function export()
    {

        $suppliers = Supplier::orderBy('name', 'ASC')->get();

        $csv = "Nama,Alamat,No Telepon,Email,Status,Keterangan\n";
        foreach ($suppliers as $supplier) {
            $csv .= '"' . $supplier->name . '",';
            $csv .= '"' . $supplier->address . '",';
            $csv .= '"' . $supplier->phone . '",';
            $csv .= '"' . $supplier->email . '",';
            $csv .= '"' . $supplier->status . '",';
            $csv .= '"' . ($supplier->description ?? '') . '"';
            $csv .= "\n";
        }

        $filename = 'suppliers_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    // Method untuk import data supplier
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
                if (count($row) < 5) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                    continue;
                }

                try {
                    Supplier::create([
                        'name' => $row[0],
                        'address' => $row[1],
                        'phone' => $row[2],
                        'email' => $row[3],
                        'status' => in_array(strtolower($row[4]), ['active', 'inactive']) ? strtolower($row[4]) : 'active',
                        'description' => $row[5] ?? null
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
                    'activity' => 'Import supplier: ' . $imported . ' data berhasil diimport',
                    'created_at' => now()
                ]);

                return redirect()->route('suppliers.index')
                    ->with('success', "Berhasil mengimport {$imported} supplier");
            } else {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'Import gagal: ' . implode(', ', array_slice($errors, 0, 5)));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error importing suppliers: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
