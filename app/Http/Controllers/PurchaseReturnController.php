<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $purchaseReturns = PurchaseReturn::with(['supplier', 'purchaseOrder', 'creator'])
            ->orderBy('return_date', 'desc')
            ->get();

        return view('purchase_returns.index', compact('purchaseReturns'));
    }

    public function create()
    {
        $purchases = PurchaseOrder::with('supplier')
            ->where('status', 'completed')
            ->orderBy('purchase_date', 'desc')
            ->get();

        return view('purchase_returns.create', compact('purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_item_id' => 'required|exists:purchases,id',
            'return_date' => 'required|date',
            'return_reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.original_item_id' => 'required|exists:purchase_items,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.return_quantity' => 'required|numeric|min:0.01',
            'items.*.return_base_quantity' => 'required|numeric|min:0.01',
            'items.*.original_price' => 'required|numeric|min:0',
            'items.*.return_price' => 'required|numeric|min:0',
            'items.*.condition' => 'required|string|max:50',
            'items.*.item_notes' => 'nullable|string|max:255'
        ]);

        $purchase = PurchaseOrder::findOrFail($request->original_item_id);

        $purchaseReturn = PurchaseReturn::create([
            'return_number' => $this->generateReturnNumber(),
            'return_date' => $request->return_date,
            'return_type' => 'purchase',
            'original_item_type' => 'purchase',
            'original_item_id' => $request->original_item_id,
            'original_order_type' => 'purchase_order',
            'supplier_id' => $purchase->supplier_id,
            'total_return_amount' => 0,
            'status' => 'pending',
            'return_reason' => $request->return_reason,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]);

        $totalAmount = 0;
        foreach ($request->items as $itemData) {
            $subtotal = $itemData['return_quantity'] * $itemData['return_price'];
            $totalAmount += $subtotal;

            PurchaseReturnItem::create([
                'return_id' => $purchaseReturn->id,
                'original_item_type' => 'purchase_item',
                'original_item_id' => $itemData['original_item_id'],
                'item_id' => $itemData['item_id'],
                'unit_id' => $itemData['unit_id'],
                'return_quantity' => $itemData['return_quantity'],
                'return_base_quantity' => $itemData['return_base_quantity'],
                'original_price' => $itemData['original_price'],
                'return_price' => $itemData['return_price'],
                'subtotal' => $subtotal,
                'condition' => $itemData['condition'],
                'item_notes' => $itemData['item_notes'] ?? null
            ]);
        }

        $purchaseReturn->update([
            'total_return_amount' => $totalAmount,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Retur pembelian berhasil dibuat');
    }

    public function show($id)
    {
        $purchaseReturn = PurchaseReturn::with(['originalPurchase', 'supplier', 'items.item', 'creator', 'approver'])
            ->findOrFail($id);

        return view('purchase_returns.show', compact('purchaseReturn'));
    }

    public function edit($id)
    {
        $purchaseReturn = PurchaseReturn::with(['originalPurchase', 'items'])
            ->findOrFail($id);

        if ($purchaseReturn->status !== 'pending') {
            return redirect()->route('purchase-returns.index')
                ->with('error', 'Retur yang sudah diproses tidak dapat diedit');
        }

        $purchases = PurchaseOrder::with('supplier')
            ->where('status', 'completed')
            ->orderBy('purchase_date', 'desc')
            ->get();

        return view('purchase_returns.edit', compact('purchaseReturn', 'purchases'));
    }

    public function update(Request $request, $id)
    {
        $purchaseReturn = PurchaseReturn::findOrFail($id);

        if ($purchaseReturn->status !== 'pending') {
            return redirect()->route('purchase-returns.index')
                ->with('error', 'Retur yang sudah diproses tidak dapat diedit');
        }

        $request->validate([
            'return_date' => 'required|date',
            'return_reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.original_item_id' => 'required|exists:purchase_items,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.return_quantity' => 'required|numeric|min:0.01',
            'items.*.return_base_quantity' => 'required|numeric|min:0.01',
            'items.*.original_price' => 'required|numeric|min:0',
            'items.*.return_price' => 'required|numeric|min:0',
            'items.*.condition' => 'required|string|max:50',
            'items.*.item_notes' => 'nullable|string|max:255'
        ]);

        $purchaseReturn->update([
            'return_date' => $request->return_date,
            'return_reason' => $request->return_reason,
            'notes' => $request->notes,
            'updated_by' => auth()->id()
        ]);

        // Delete old items and create new ones
        $purchaseReturn->items()->delete();

        $totalAmount = 0;
        foreach ($request->items as $itemData) {
            $subtotal = $itemData['return_quantity'] * $itemData['return_price'];
            $totalAmount += $subtotal;

            PurchaseReturnItem::create([
                'return_id' => $purchaseReturn->id,
                'original_item_type' => 'purchase_item',
                'original_item_id' => $itemData['original_item_id'],
                'item_id' => $itemData['item_id'],
                'unit_id' => $itemData['unit_id'],
                'return_quantity' => $itemData['return_quantity'],
                'return_base_quantity' => $itemData['return_base_quantity'],
                'original_price' => $itemData['original_price'],
                'return_price' => $itemData['return_price'],
                'subtotal' => $subtotal,
                'condition' => $itemData['condition'],
                'item_notes' => $itemData['item_notes'] ?? null
            ]);
        }

        $purchaseReturn->update([
            'total_return_amount' => $totalAmount,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Retur pembelian berhasil diupdate');
    }

    public function destroy($id)
    {
        $purchaseReturn = PurchaseReturn::findOrFail($id);

        if ($purchaseReturn->status !== 'pending') {
            return redirect()->route('purchase-returns.index')
                ->with('error', 'Retur yang sudah diproses tidak dapat dihapus');
        }

        $purchaseReturn->items()->delete();
        $purchaseReturn->delete();

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Retur pembelian berhasil dihapus');
    }

    public function generateReturnNumber()
    {
        $prefix = 'RPB';
        $date = now()->format('Ymd');
        $lastReturn = PurchaseReturn::where('return_type', 'purchase')
            ->whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastReturn ? intval(substr($lastReturn->return_number, -4)) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function approve($id)
    {
        $purchaseReturn = PurchaseReturn::findOrFail($id);

        $purchaseReturn->update([
            'status' => 'approved',
            'approved_date' => now(),
            'approved_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]);

        // Update stock items
        foreach ($purchaseReturn->items as $returnItem) {
            $stockItem = Item::findOrFail($returnItem->item_id);
            $stockItem->decrement('stock', $returnItem->return_quantity);
        }

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Retur pembelian berhasil disetujui');
    }

    public function reject($id)
    {
        $purchaseReturn = PurchaseReturn::findOrFail($id);

        $purchaseReturn->update([
            'status' => 'rejected',
            'approved_date' => now(),
            'approved_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Retur pembelian ditolak');
    }

    public function print($id)
    {
        $purchaseReturn = PurchaseReturn::with(['originalPurchase', 'supplier', 'items.item'])
            ->findOrFail($id);

        return view('purchase_returns.print', compact('purchaseReturn'));
    }

    public function export()
    {
        // Export functionality
        $purchaseReturns = PurchaseReturn::purchaseReturns()
            ->with(['supplier', 'originalPurchase', 'items.item'])
            ->get();

        // Add export logic here (Excel, PDF, etc.)
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
}