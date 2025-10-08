<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\ActivityLog;

class SalesReturnController extends Controller
{
    public function index()
    {
        $salesReturns = SalesReturn::with(['customer', 'salesOrder', 'creator'])
            ->orderBy('return_date', 'desc')
            ->get();

        return view('sales_returns.index', compact('salesReturns'));
    }

    public function show($id)
    {
        $salesReturn = SalesReturn::with([
            'customer',
            'salesOrder',
            'items.item',
            'items.unit',
            'creator',
            'approver',
            'updater'
        ])->findOrFail($id);

        return view('sales_returns.show', compact('salesReturn'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $salesOrders = SalesOrder::where('status', 'completed')
            ->with(['customer'])
            ->orderBy('order_date', 'desc')
            ->get();
        $items = Item::select('id', 'item_name', 'stock', 'sell_price', 'unit_id')
            ->orderBy('item_name')->get();
        $units = Unit::orderBy('unit_name')->get();

        // Unit conversion mapping
        $unitConversionMap = [];
        foreach ($items as $item) {
            $conversions = DB::table('unit_conversions')
                ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
                ->select(
                    'unit_conversions.unit_id as id_satuan',
                    'units.unit_name as nama_satuan',
                    'unit_conversions.conversion_value as konversi'
                )
                ->where('unit_conversions.item_id', $item->id)
                ->get()
                ->toArray();
            $unitConversionMap[$item->id] = $conversions;
        }

        return view('sales_returns.create', compact(
            'customers',
            'salesOrders',
            'items',
            'units',
            'unitConversionMap'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'required|date',
            'return_number' => 'required|string|unique:sales_returns,return_number',
            'reason' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.unit_id' => 'required|exists:units,id',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create sales return
            $salesReturn = SalesReturn::create([
                'return_number' => $request->return_number,
                'sales_order_id' => $request->sales_order_id,
                'customer_id' => $request->customer_id,
                'return_date' => $request->return_date,
                'total_amount' => $request->total_amount,
                'reason' => $request->reason,
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);

            // Create sales return items
            foreach ($request->details as $detail) {
                SalesReturnItem::create([
                    'sales_return_id' => $salesReturn->id,
                    'item_id' => $detail['item_id'],
                    'unit_id' => $detail['unit_id'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'table_name' => 'sales_returns',
                'record_id' => $salesReturn->id,
                'description' => "Created sales return {$salesReturn->return_number}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('sales-returns.index')
                ->with('success', 'Retur penjualan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating sales return: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal membuat retur penjualan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $salesReturn = SalesReturn::with(['items.item', 'items.unit', 'customer', 'salesOrder'])
            ->findOrFail($id);

        if ($salesReturn->status !== 'pending') {
            return redirect()->route('sales-returns.index')
                ->with('error', 'Hanya retur dengan status pending yang dapat diedit!');
        }

        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $salesOrders = SalesOrder::where('status', 'completed')
            ->with(['customer'])
            ->orderBy('order_date', 'desc')
            ->get();
        $items = Item::select('id', 'item_name', 'stock', 'sell_price', 'unit_id')
            ->orderBy('item_name')->get();
        $units = Unit::orderBy('unit_name')->get();

        // Unit conversion mapping
        $unitConversionMap = [];
        foreach ($items as $item) {
            $conversions = DB::table('unit_conversions')
                ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
                ->select(
                    'unit_conversions.unit_id as id_satuan',
                    'units.unit_name as nama_satuan',
                    'unit_conversions.conversion_value as konversi'
                )
                ->where('unit_conversions.item_id', $item->id)
                ->get()
                ->toArray();
            $unitConversionMap[$item->id] = $conversions;
        }

        $details = $salesReturn->items;

        return view('sales_returns.edit', compact(
            'salesReturn',
            'details',
            'customers',
            'salesOrders',
            'items',
            'units',
            'unitConversionMap'
        ));
    }

    public function update(Request $request, $id)
    {
        $salesReturn = SalesReturn::findOrFail($id);

        if ($salesReturn->status !== 'pending') {
            return redirect()->route('sales-returns.index')
                ->with('error', 'Hanya retur dengan status pending yang dapat diedit!');
        }

        $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'required|date',
            'return_number' => 'required|string|unique:sales_returns,return_number,' . $id,
            'reason' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.unit_id' => 'required|exists:units,id',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Update sales return
            $salesReturn->update([
                'return_number' => $request->return_number,
                'sales_order_id' => $request->sales_order_id,
                'customer_id' => $request->customer_id,
                'return_date' => $request->return_date,
                'total_amount' => $request->total_amount,
                'reason' => $request->reason,
                'updated_by' => auth()->id(),
            ]);

            // Delete existing items
            SalesReturnItem::where('sales_return_id', $salesReturn->id)->delete();

            // Create new items
            foreach ($request->details as $detail) {
                SalesReturnItem::create([
                    'sales_return_id' => $salesReturn->id,
                    'item_id' => $detail['item_id'],
                    'unit_id' => $detail['unit_id'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'table_name' => 'sales_returns',
                'record_id' => $salesReturn->id,
                'description' => "Updated sales return {$salesReturn->return_number}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('sales-returns.index')
                ->with('success', 'Retur penjualan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating sales return: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui retur penjualan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $salesReturn = SalesReturn::findOrFail($id);

        if ($salesReturn->status !== 'pending') {
            return redirect()->route('sales-returns.index')
                ->with('error', 'Hanya retur dengan status pending yang dapat dihapus!');
        }

        DB::beginTransaction();
        try {
            // Delete items first
            SalesReturnItem::where('sales_return_id', $salesReturn->id)->delete();

            // Delete sales return
            $returnNumber = $salesReturn->return_number;
            $salesReturn->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'table_name' => 'sales_returns',
                'record_id' => $id,
                'description' => "Deleted sales return {$returnNumber}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('sales-returns.index')
                ->with('success', 'Retur penjualan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting sales return: ' . $e->getMessage());
            return redirect()->route('sales-returns.index')
                ->with('error', 'Gagal menghapus retur penjualan: ' . $e->getMessage());
        }
    }

    // Approve sales return
    public function approve($id)
    {
        $salesReturn = SalesReturn::findOrFail($id);

        if ($salesReturn->status !== 'pending') {
            return redirect()->route('sales-returns.index')
                ->with('error', 'Hanya retur dengan status pending yang dapat disetujui!');
        }

        DB::beginTransaction();
        try {
            // Update status to approved
            $salesReturn->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update stock for returned items
            foreach ($salesReturn->items as $item) {
                $currentStock = Item::find($item->item_id)->stock;
                Item::where('id', $item->item_id)
                    ->update(['stock' => $currentStock + $item->quantity]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'approve',
                'table_name' => 'sales_returns',
                'record_id' => $salesReturn->id,
                'description' => "Approved sales return {$salesReturn->return_number}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('sales-returns.index')
                ->with('success', 'Retur penjualan berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error approving sales return: ' . $e->getMessage());
            return redirect()->route('sales-returns.index')
                ->with('error', 'Gagal menyetujui retur penjualan: ' . $e->getMessage());
        }
    }

    // Reject sales return
    public function reject($id)
    {
        $salesReturn = SalesReturn::findOrFail($id);

        if ($salesReturn->status !== 'pending') {
            return redirect()->route('sales-returns.index')
                ->with('error', 'Hanya retur dengan status pending yang dapat ditolak!');
        }

        $salesReturn->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject',
            'table_name' => 'sales_returns',
            'record_id' => $salesReturn->id,
            'description' => "Rejected sales return {$salesReturn->return_number}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('sales-returns.index')
            ->with('success', 'Retur penjualan berhasil ditolak!');
    }

    // Generate return number
    private function generateReturnNumber($returnDate)
    {
        $date = date('Ymd', strtotime($returnDate));
        $lastReturn = SalesReturn::whereDate('return_date', $returnDate)
            ->orderBy('return_number', 'desc')
            ->first();

        if ($lastReturn) {
            $lastNumber = intval(substr($lastReturn->return_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'SR-' . $date . '-' . $newNumber;
    }

    // AJAX untuk generate nomor retur
    public function generateReturnNumberAjax(Request $request)
    {
        $returnDate = $request->input('return_date');
        $returnNumber = $this->generateReturnNumber($returnDate);

        return response()->json(['return_number' => $returnNumber]);
    }
}
