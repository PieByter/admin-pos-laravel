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
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('items', $permissions) || in_array('items_view', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('items', $permissions) || in_array('items_create', $permissions);
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

        $items = DB::table('items')
            ->leftJoin('item_categories', 'item_categories.id', '=', 'items.category_id')
            ->leftJoin('item_groups', 'item_groups.id', '=', 'items.group_id')
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->select(
                'items.*',
                'item_categories.name as category_name',
                'item_groups.name as group_name',
                'units.name as unit_name'
            )
            ->orderBy('items.id', 'ASC')
            ->get()
            ->toArray();

        $data = array_merge($this->getPermissionData(), [
            'title' => 'Manajemen Barang',
            'items' => $items,
        ]);

        return view('items.index', $data);
    }

    public function create()
    {
        $this->requireWriteAccess();

        $data = [
            'categories' => ItemCategory::all()->toArray(),
            'groups' => ItemGroup::all()->toArray(),
            'units' => Unit::all()->toArray(),
            'title' => 'Tambah Data Barang Baru'
        ];

        return view('items.create', $data);
    }

    public function store(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required',
            'category_id' => 'required|integer|exists:item_categories,id',
            'group_id' => 'required|integer|exists:item_groups,id',
            'unit_id' => 'required|integer|exists:units,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock' => 'required|integer',
        ], [
            'code.required' => 'Kode barang wajib diisi',
            'code.unique' => 'Kode barang sudah digunakan',
            'name.required' => 'Nama barang wajib diisi',
            'category_id.required' => 'Jenis barang wajib dipilih',
            'group_id.required' => 'Group barang wajib dipilih',
            'unit_id.required' => 'Satuan wajib dipilih',
            'purchase_price.required' => 'Harga beli wajib diisi',
            'selling_price.required' => 'Harga jual wajib diisi',
            'stock.required' => 'Stok wajib diisi',
        ]);

        Item::create([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'group_id' => $request->group_id,
            'unit_id' => $request->unit_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah data barang: ' . $request->name,
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
        $this->requireReadAccess();

        $item = DB::table('items')
            ->leftJoin('item_categories', 'item_categories.id', '=', 'items.category_id')
            ->leftJoin('item_groups', 'item_groups.id', '=', 'items.group_id')
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->select(
                'items.*',
                'item_categories.name as category_name',
                'item_groups.name as group_name',
                'units.name as unit_name'
            )
            ->where('items.id', $id)
            ->first();

        if (!$item) {
            return redirect()->route('items.index')
                ->with('error', 'Barang tidak ditemukan');
        }

        $conversions = DB::table('unit_conversions')
            ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
            ->select('unit_conversions.*', 'units.name as unit_name')
            ->where('unit_conversions.item_id', $id)
            ->get()
            ->toArray();

        $data = array_merge($this->getPermissionData(), [
            'title' => 'Detail Barang',
            'item' => $item,
            'conversions' => $conversions,
        ]);

        return view('items.show', $data);
    }

    public function edit($id)
    {
        $this->requireWriteAccess();

        $item = Item::find($id);
        if (!$item) {
            return redirect()->route('items.index')
                ->with('error', 'Barang tidak ditemukan');
        }

        $data = [
            'item' => $item->toArray(),
            'categories' => ItemCategory::all()->toArray(),
            'groups' => ItemGroup::all()->toArray(),
            'units' => Unit::all()->toArray(),
            'title' => 'Edit Data Barang'
        ];

        return view('items.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

        $item = Item::find($id);
        if (!$item) {
            return redirect()->route('items.index')
                ->with('error', 'Barang tidak ditemukan');
        }

        $request->validate([
            'code' => 'required|unique:items,code,' . $id,
            'name' => 'required',
            'category_id' => 'required|integer|exists:item_categories,id',
            'group_id' => 'required|integer|exists:item_groups,id',
            'unit_id' => 'required|integer|exists:units,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock' => 'required|integer',
        ], [
            'code.required' => 'Kode barang wajib diisi',
            'code.unique' => 'Kode barang sudah digunakan',
            'name.required' => 'Nama barang wajib diisi',
            'category_id.required' => 'Jenis barang wajib dipilih',
            'group_id.required' => 'Group barang wajib dipilih',
            'unit_id.required' => 'Satuan wajib dipilih',
            'purchase_price.required' => 'Harga beli wajib diisi',
            'selling_price.required' => 'Harga jual wajib diisi',
            'stock.required' => 'Stok wajib diisi',
        ]);

        $item->update([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'group_id' => $request->group_id,
            'unit_id' => $request->unit_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengedit data barang: ' . $request->name,
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
        $this->requireWriteAccess();

        $item = Item::find($id);
        if (!$item) {
            return redirect()->route('items.index')
                ->with('error', 'Barang tidak ditemukan');
        }

        $itemName = $item->name;
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
            'name' => 'required'
        ]);

        $unit = Unit::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'id' => $unit->id,
            'name' => $unit->name
        ]);
    }

    public function ajaxSaveCategory(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $category = ItemCategory::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description
        ]);
    }

    public function ajaxSaveGroup(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $group = ItemGroup::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description
        ]);
    }
}