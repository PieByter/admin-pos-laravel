<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ItemGroup;
use App\Models\Item;
use App\Models\ActivityLog;

class ItemGroupController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('group_barang', $permissions) || in_array('group_barang_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('group_barang', $permissions);
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

        $groups = ItemGroup::orderBy('name', 'ASC')->get()->toArray();

        $data = array_merge($this->getPermissionData(), [
            'groups' => $groups,
            'title' => 'Daftar Group Barang'
        ]);

        return view('item_groups.index', $data);
    }

    public function create()
    {
        $this->requireWriteAccess();

        return view('item_groups.create', [
            'title' => 'Tambah Group Barang'
        ]);
    }

    public function store(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'name' => 'required|string|max:50|unique:item_groups,name',
            'description' => 'nullable|string|max:100'
        ], [
            'name.required' => 'Nama group barang wajib diisi',
            'name.max' => 'Nama group barang maksimal 50 karakter',
            'name.unique' => 'Nama group barang sudah digunakan',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        ItemGroup::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah group barang: ' . $request->name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging save item group: ' . $logError->getMessage());
        }

        return redirect()->route('item-groups.index')
            ->with('success', 'Group barang berhasil ditambahkan!');
    }

    public function show($id)
    {
        $this->requireReadAccess();

        $group = ItemGroup::find($id);
        if (!$group) {
            return redirect()->route('item-groups.index')
                ->with('error', 'Group barang tidak ditemukan');
        }

        // Ambil barang yang menggunakan group ini
        $items = Item::where('group_id', $id)
            ->select('id', 'code', 'name', 'stock')
            ->get()
            ->toArray();

        $data = array_merge($this->getPermissionData(), [
            'group' => $group->toArray(),
            'items' => $items,
            'title' => 'Detail Group Barang'
        ]);

        return view('item_groups.show', $data);
    }

    public function edit($id)
    {
        $this->requireWriteAccess();

        $group = ItemGroup::find($id);
        if (!$group) {
            return redirect()->route('item-groups.index')
                ->with('error', 'Group barang tidak ditemukan!');
        }

        return view('item_groups.edit', [
            'group' => $group->toArray(),
            'title' => 'Edit Group Barang'
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

        $group = ItemGroup::find($id);
        if (!$group) {
            return redirect()->route('item-groups.index')
                ->with('error', 'Group barang tidak ditemukan!');
        }

        $request->validate([
            'name' => 'required|string|max:50|unique:item_groups,name,' . $id,
            'description' => 'nullable|string|max:100'
        ], [
            'name.required' => 'Nama group barang wajib diisi',
            'name.max' => 'Nama group barang maksimal 50 karakter',
            'name.unique' => 'Nama group barang sudah digunakan',
            'description.max' => 'Keterangan maksimal 100 karakter'
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate group barang: ' . $request->name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging update item group: ' . $logError->getMessage());
        }

        return redirect()->route('item-groups.index')
            ->with('success', 'Group barang berhasil diupdate!');
    }

    public function destroy($id)
    {
        $this->requireWriteAccess();

        $group = ItemGroup::find($id);
        if (!$group) {
            return redirect()->route('item-groups.index')
                ->with('error', 'Group barang tidak ditemukan!');
        }

        // Cek apakah masih digunakan oleh barang
        $itemCount = Item::where('group_id', $id)->count();

        if ($itemCount > 0) {
            return redirect()->route('item-groups.index')
                ->with('error', 'Group barang tidak dapat dihapus karena masih digunakan oleh ' . $itemCount . ' barang.');
        }

        try {
            $groupName = $group->name;
            $group->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus group barang: ' . $groupName,
                'created_at' => now()
            ]);

            return redirect()->route('item-groups.index')
                ->with('success', 'Group barang berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting item group: ' . $e->getMessage());
            return redirect()->route('item-groups.index')
                ->with('error', 'Terjadi kesalahan saat menghapus group barang.');
        }
    }

    // Method tambahan untuk AJAX get groups
    public function getGroups()
    {
        $this->requireReadAccess();

        $groups = ItemGroup::select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json($groups);
    }

    // Method untuk bulk delete
    public function bulkDelete(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'group_ids' => 'required|array',
            'group_ids.*' => 'integer|exists:item_groups,id'
        ]);

        $groupIds = $request->group_ids;

        // Cek apakah ada group yang masih digunakan
        $usedGroups = Item::whereIn('group_id', $groupIds)
            ->join('item_groups', 'item_groups.id', '=', 'items.group_id')
            ->select('item_groups.name')
            ->distinct()
            ->pluck('name')
            ->toArray();

        if (!empty($usedGroups)) {
            return redirect()->route('item-groups.index')
                ->with('error', 'Group berikut masih digunakan: ' . implode(', ', $usedGroups));
        }

        try {
            $groups = ItemGroup::whereIn('id', $groupIds)->get();
            $groupNames = $groups->pluck('name')->toArray();

            ItemGroup::whereIn('id', $groupIds)->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus bulk group barang: ' . implode(', ', $groupNames),
                'created_at' => now()
            ]);

            return redirect()->route('item-groups.index')
                ->with('success', 'Group barang terpilih berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error bulk deleting item groups: ' . $e->getMessage());
            return redirect()->route('item-groups.index')
                ->with('error', 'Terjadi kesalahan saat menghapus group barang.');
        }
    }

    // Method untuk export data
    public function export()
    {
        $this->requireReadAccess();

        $groups = ItemGroup::orderBy('name')->get();

        $filename = 'item_groups_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($groups) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama', 'Keterangan', 'Jumlah Barang', 'Dibuat', 'Diupdate']);

            foreach ($groups as $group) {
                $itemCount = Item::where('group_id', $group->id)->count();
                fputcsv($file, [
                    $group->id,
                    $group->name,
                    $group->description,
                    $itemCount,
                    $group->created_at,
                    $group->updated_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Method untuk move items to another group
    public function moveItems(Request $request, $fromGroupId)
    {
        $this->requireWriteAccess();

        $request->validate([
            'to_group_id' => 'required|integer|exists:item_groups,id|different:from_group_id',
            'item_ids' => 'array',
            'item_ids.*' => 'integer|exists:items,id'
        ]);

        $fromGroup = ItemGroup::find($fromGroupId);
        $toGroup = ItemGroup::find($request->to_group_id);

        if (!$fromGroup || !$toGroup) {
            return response()->json(['error' => 'Group tidak ditemukan'], 404);
        }

        try {
            $query = Item::where('group_id', $fromGroupId);

            // Jika ada item_ids spesifik, filter hanya item tersebut
            if (!empty($request->item_ids)) {
                $query->whereIn('id', $request->item_ids);
            }

            $movedCount = $query->update(['group_id' => $request->to_group_id]);

            // Log aktivitas
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => "Memindahkan {$movedCount} barang dari group '{$fromGroup->name}' ke '{$toGroup->name}'",
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$movedCount} barang berhasil dipindahkan",
                'moved_count' => $movedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error moving items: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memindahkan barang'], 500);
        }
    }
}