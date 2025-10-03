<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\ActivityLog;

class CustomerController extends Controller
{
    // private function canRead(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('customer', $permissions) || in_array('customer_read', $permissions);
    // }

    // private function canWrite(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('customer', $permissions);
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

    // Tampilkan daftar customer
    public function index()
    {
        $customers = Customer::orderBy('name', 'ASC')->get();

        $data =  [
            'title' => 'Manajemen Customer',
            'customers' => $customers,
        ];

        return view('customers.index', $data);
    }

    // Tampilkan form tambah customer
    public function create()
    {
        $data = [
            'title' => 'Tambah Customer Baru'
        ];

        return view('customers.create', $data);
    }

    // Simpan customer baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'contact_email' => 'required|email|unique:customers,contact_email',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Nama customer wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'phone_number.required' => 'No. telepon wajib diisi',
            'contact_email.required' => 'Email wajib diisi',
            'contact_email.email' => 'Format email tidak valid',
            'contact_email.unique' => 'Email sudah digunakan customer lain',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive'
        ]);

        Customer::create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'contact_email' => $request->contact_email,
            'status' => $request->status ?? 'active',
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah customer: ' . $request->name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging save customer: ' . $logError->getMessage());
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    // Tampilkan detail customer
    public function show($id)
    {

        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customers.index')
                ->with('error', 'Customer tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Customer',
            'customer' => $customer
        ];

        return view('customers.show', $data);
    }

    // Tampilkan form edit customer
    public function edit($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customers.index')
                ->with('error', 'Customer tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Data Customer',
            'customer' => $customer
        ];

        return view('customers.edit', $data);
    }

    // Update customer
    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customers.index')
                ->with('error', 'Customer tidak ditemukan');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'contact_email' => 'required|email|unique:customers,contact_email,' . $id,
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Nama customer wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'phone_number.required' => 'No. telepon wajib diisi',
            'contact_email.required' => 'Email wajib diisi',
            'contact_email.email' => 'Format email tidak valid',
            'contact_email.unique' => 'Email sudah digunakan customer lain',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive'
        ]);

        $customer->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'contact_email' => $request->contact_email,
            'status' => $request->status ?? $customer->status,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate customer: ' . $request->name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging update customer: ' . $logError->getMessage());
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diupdate');
    }

    // Hapus customer
    public function destroy($id)
    {

        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customers.index')
                ->with('error', 'Customer tidak ditemukan');
        }

        $customerName = $customer->name;
        $customer->delete();

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus customer: ' . $customerName,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging delete customer: ' . $logError->getMessage());
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus');
    }

    // Method tambahan untuk change status
    public function changeStatus($id, $status)
    {

        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['error' => 'Customer tidak ditemukan'], 404);
        }

        $allowedStatuses = ['active', 'inactive', 'suspended'];
        if (!in_array($status, $allowedStatuses)) {
            return response()->json(['error' => 'Status tidak valid'], 400);
        }

        $customer->update(['status' => $status]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengubah status customer: ' . $customer->name . ' menjadi ' . $status,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging change status customer: ' . $logError->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Status customer berhasil diubah',
            'status' => $status
        ]);
    }

    // Method untuk search customers (AJAX)
    public function search(Request $request)
    {

        $query = $request->get('q');
        $customers = Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orderBy('name', 'ASC')
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($customers);
    }

    // Method untuk export customers (opsional)
    public function export()
    {

        $customers = Customer::orderBy('name', 'ASC')->get();

        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama', 'Alamat', 'Telepon', 'Email', 'Status', 'Keterangan', 'Dibuat']);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->address,
                    $customer->phone,
                    $customer->email,
                    $customer->status,
                    $customer->description,
                    $customer->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
