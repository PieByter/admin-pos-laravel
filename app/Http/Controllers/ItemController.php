<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemGroup;
use App\Models\Unit;
use App\Models\UnitConversion;
use App\Models\ActivityLog;

class ItemController extends Controller
{
    // private function canRead(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('items', $permissions) || in_array('items_view', $permissions);
    // }

    // private function canWrite(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('items', $permissions) || in_array('items_create', $permissions);
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

    public function index()
    {
        // Gunakan Eloquent dengan eager loading
        $items = Item::with(['itemCategory', 'itemGroup', 'unit'])
            ->orderBy('id', 'ASC')
            ->get();

        $data = [
            'title' => 'Manajemen Barang',
            'items' => $items,
        ];

        return view('items.index', $data);
    }

    public function create()
    {
        $data = [
            'categories' => ItemCategory::all(),
            'groups' => ItemGroup::all(),
            'units' => Unit::all(),
            'title' => 'Tambah Data Barang Baru'
        ];

        return view('items.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:items,item_code',
            'item_name' => 'required',
            'item_category_id' => 'required|integer|exists:item_categories,id',
            'item_group_id' => 'required|integer|exists:item_groups,id',
            'unit_id' => 'required|integer|exists:units,id',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock' => 'required|integer',
        ], [
            'item_code.required' => 'Kode barang wajib diisi',
            'item_code.unique' => 'Kode barang sudah digunakan',
            'item_name.required' => 'Nama barang wajib diisi',
            'item_category_id.required' => 'Jenis barang wajib dipilih',
            'item_group_id.required' => 'Group barang wajib dipilih',
            'unit_id.required' => 'Satuan wajib dipilih',
            'buy_price.required' => 'Harga beli wajib diisi',
            'sell_price.required' => 'Harga jual wajib diisi',
            'stock.required' => 'Stok wajib diisi',
        ]);

        Item::create([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'item_category_id' => $request->item_category_id,
            'item_group_id' => $request->item_group_id,
            'unit_id' => $request->unit_id,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock,
            'item_description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah data barang: ' . $request->item_name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging activity: ' . $logError->getMessage());
        }

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function show($id)
    {
        $item = Item::with(['itemCategory', 'itemGroup', 'unit', 'unitConversions.fromUnit', 'unitConversions.toUnit'])
            ->findOrFail($id);

        $data = [
            'title' => 'Detail Barang',
            'item' => $item,
        ];

        return view('items.show', $data);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);

        $data = [
            'item' => $item,
            'categories' => ItemCategory::all(),
            'groups' => ItemGroup::all(),
            'units' => Unit::all(),
            'title' => 'Edit Data Barang'
        ];

        return view('items.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'item_code' => 'required|unique:items,item_code,' . $id,
            'item_name' => 'required',
            'item_category_id' => 'required|integer|exists:item_categories,id',
            'item_group_id' => 'required|integer|exists:item_groups,id',
            'unit_id' => 'required|integer|exists:units,id',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock' => 'required|integer',
        ], [
            'item_code.required' => 'Kode barang wajib diisi',
            'item_code.unique' => 'Kode barang sudah digunakan',
            'item_name.required' => 'Nama barang wajib diisi',
            'item_category_id.required' => 'Jenis barang wajib dipilih',
            'item_group_id.required' => 'Group barang wajib dipilih',
            'unit_id.required' => 'Satuan wajib dipilih',
            'buy_price.required' => 'Harga beli wajib diisi',
            'sell_price.required' => 'Harga jual wajib diisi',
            'stock.required' => 'Stok wajib diisi',
        ]);

        $item->update([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'item_category_id' => $request->item_category_id,
            'item_group_id' => $request->item_group_id,
            'unit_id' => $request->unit_id,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock,
            'item_description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengedit data barang: ' . $request->item_name,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging activity: ' . $logError->getMessage());
        }

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $itemName = $item->item_name;

        $item->delete();

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus data barang: ' . $itemName,
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging activity: ' . $logError->getMessage());
        }

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    // Ajax Methods
    public function ajaxSaveUnit(Request $request)
    {
        $request->validate([
            'name' => 'required'  // ✅ Ganti 'unit_name' dengan 'name' (dari form)
        ]);

        $unit = Unit::create([
            'unit_name' => $request->name,  // ✅ Save ke field 'unit_name' di database
            'description' => $request->description
        ]);

        return response()->json([
            'id' => $unit->id,
            'name' => $unit->unit_name  // ✅ Return 'unit_name' sebagai 'name'
        ]);
    }

    public function ajaxSaveCategory(Request $request)
    {
        $request->validate([
            'name' => 'required'  // ✅ Ganti 'category_name' dengan 'name' (dari form)
        ]);

        $category = ItemCategory::create([
            'category_name' => $request->name,  // ✅ Save ke field 'category_name' di database
            'description' => $request->description
        ]);

        return response()->json([
            'id' => $category->id,
            'name' => $category->category_name  // ✅ Return 'category_name' sebagai 'name'
        ]);
    }

    public function ajaxSaveGroup(Request $request)
    {
        $request->validate([
            'name' => 'required'  // ✅ Ganti 'group_name' dengan 'name' (dari form)
        ]);

        $group = ItemGroup::create([
            'group_name' => $request->name,  // ✅ Save ke field 'group_name' di database
            'description' => $request->description
        ]);

        return response()->json([
            'id' => $group->id,
            'name' => $group->group_name  // ✅ Return 'group_name' sebagai 'name'
        ]);
    }
}
