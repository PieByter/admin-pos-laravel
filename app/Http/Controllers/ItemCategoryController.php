<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ActivityLog;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemCategoryController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('jenis_barang', $permissions) || in_array('jenis_barang_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('jenis_barang', $permissions);
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

    public function index()
    {
        $this->requireReadAccess();

        $categories = ItemCategory::orderBy('name', 'ASC')->get()->toArray();

        $data = array_merge($this->getPermissionData(), [
            'categories' => $categories,
            'title' => 'Daftar Jenis Barang'
        ]);

        return view('item_categories.index', $data);
    }

    public function create()
    {
        $this->requireWriteAccess();

        return view('item_categories.create', [
            'title' => 'Tambah Jenis Barang'
        ]);
    }

    public function store(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'name' => 'required|string|max:50|unique:item_categories,name',
            'description' => 'nullable|string|max:100'
        ], [
            'name.required' => 'Nama jenis barang wajib diisi',
            'name.max' => 'Nama jenis barang maksimal 50 karakter',
            'name.unique' => 'Nama jenis barang sudah digunakan',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        ItemCategory::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah jenis barang: ' . $request->name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging save item category: ' . $logError->getMessage());
        }

        return redirect()->route('item-categories.index')
            ->with('success', 'Jenis barang berhasil ditambahkan!');
    }

    public function show($id)
    {
        $this->requireReadAccess();

        $category = ItemCategory::find($id);
        if (!$category) {
            return redirect()->route('item-categories.index')
                ->with('error', 'Jenis barang tidak ditemukan');
        }

        // Ambil barang yang menggunakan kategori ini
        $items = Item::where('category_id', $id)
            ->select('id', 'code', 'name', 'stock')
            ->get()
            ->toArray();

        $data = array_merge($this->getPermissionData(), [
            'category' => $category->toArray(),
            'items' => $items,
            'title' => 'Detail Jenis Barang'
        ]);

        return view('item_categories.show', $data);
    }

    public function edit($id)
    {
        $this->requireWriteAccess();

        $category = ItemCategory::find($id);
        if (!$category) {
            return redirect()->route('item-categories.index')
                ->with('error', 'Jenis barang tidak ditemukan!');
        }

        return view('item_categories.edit', [
            'category' => $category->toArray(),
            'title' => 'Edit Jenis Barang'
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

        $category = ItemCategory::find($id);
        if (!$category) {
            return redirect()->route('item-categories.index')
                ->with('error', 'Jenis barang tidak ditemukan!');
        }

        $request->validate([
            'name' => 'required|string|max:50|unique:item_categories,name,' . $id,
            'description' => 'nullable|string|max:100'
        ], [
            'name.required' => 'Nama jenis barang wajib diisi',
            'name.max' => 'Nama jenis barang maksimal 50 karakter',
            'name.unique' => 'Nama jenis barang sudah digunakan',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate jenis barang: ' . $request->name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging update item category: ' . $logError->getMessage());
        }

        return redirect()->route('item-categories.index')
            ->with('success', 'Jenis barang berhasil diupdate!');
    }

    public function destroy($id)
    {
        $this->requireWriteAccess();

        $category = ItemCategory::find($id);
        if (!$category) {
            return redirect()->route('item-categories.index')
                ->with('error', 'Jenis barang tidak ditemukan!');
        }

        // Cek apakah masih digunakan oleh barang
        $itemCount = Item::where('category_id', $id)->count();

        if ($itemCount > 0) {
            return redirect()->route('item-categories.index')
                ->with('error', 'Jenis barang tidak dapat dihapus karena masih digunakan oleh ' . $itemCount . ' barang.');
        }

        try {
            $categoryName = $category->name;
            $category->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus jenis barang: ' . $categoryName,
                'created_at' => now()
            ]);

            return redirect()->route('item-categories.index')
                ->with('success', 'Jenis barang berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting item category: ' . $e->getMessage());
            return redirect()->route('item-categories.index')
                ->with('error', 'Terjadi kesalahan saat menghapus jenis barang.');
        }
    }

    // Method tambahan untuk AJAX get categories
    public function getCategories()
    {
        $this->requireReadAccess();

        $categories = ItemCategory::select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json($categories);
    }

    // Method untuk bulk delete
    public function bulkDelete(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:item_categories,id'
        ]);

        $categoryIds = $request->category_ids;

        // Cek apakah ada kategori yang masih digunakan
        $usedCategories = Item::whereIn('category_id', $categoryIds)
            ->join('item_categories', 'item_categories.id', '=', 'items.category_id')
            ->select('item_categories.name')
            ->distinct()
            ->pluck('name')
            ->toArray();

        if (!empty($usedCategories)) {
            return redirect()->route('item-categories.index')
                ->with('error', 'Kategori berikut masih digunakan: ' . implode(', ', $usedCategories));
        }

        try {
            $categories = ItemCategory::whereIn('id', $categoryIds)->get();
            $categoryNames = $categories->pluck('name')->toArray();

            ItemCategory::whereIn('id', $categoryIds)->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus bulk jenis barang: ' . implode(', ', $categoryNames),
                'created_at' => now()
            ]);

            return redirect()->route('item-categories.index')
                ->with('success', 'Jenis barang terpilih berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error bulk deleting item categories: ' . $e->getMessage());
            return redirect()->route('item-categories.index')
                ->with('error', 'Terjadi kesalahan saat menghapus jenis barang.');
        }
    }

    // Method untuk export data
    public function export()
    {
        $this->requireReadAccess();

        $categories = ItemCategory::with(['items' => function ($query) {
            $query->select('category_id', DB::raw('count(*) as total'));
        }])->orderBy('name')->get();

        $filename = 'item_categories_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama', 'Keterangan', 'Jumlah Barang', 'Dibuat', 'Diupdate']);

            foreach ($categories as $category) {
                $itemCount = Item::where('category_id', $category->id)->count();
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->description,
                    $itemCount,
                    $category->created_at,
                    $category->updated_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
