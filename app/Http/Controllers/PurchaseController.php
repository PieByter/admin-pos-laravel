<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = PurchaseOrder::with(['supplier', 'purchaseOrderItems.item', 'purchaseOrderItems.unit', 'createdBy', 'updatedBy'])
            ->orderByRaw('MONTH(issue_date) DESC')
            ->orderByRaw('YEAR(issue_date) DESC')
            ->orderBy('id', 'DESC')
            ->get();

        return view('purchase_orders.index', [
            'purchases' => $purchases,
            'title' => 'Daftar Pembelian'
        ]);
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'purchaseOrderItems.item', 'purchaseOrderItems.unit', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        return view('purchase_orders.show', [
            'purchaseOrder' => $purchaseOrder,
            'supplier' => $purchaseOrder->supplier,
            'details' => $purchaseOrder->purchaseOrderItems,
            'title' => 'Detail Pembelian'
        ]);
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        $items = Item::orderBy('item_name')->get();
        $units = Unit::orderBy('unit_name')->get();

        // Unit conversion mapping
        $unitConversionMap = [];
        foreach ($items as $item) {
            $conversions = DB::table('unit_conversions')
                ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
                ->select('unit_conversions.*', 'units.unit_name as unit_name')
                ->where('unit_conversions.item_id', $item->id)
                ->get();
            $unitConversionMap[$item->id] = $conversions;
        }

        $issueDate = date('Y-m-d');
        $invoiceNumber = $this->generateInvoiceNumber($issueDate);

        return view('purchase_orders.create', [
            'suppliers' => $suppliers,
            'items' => $items,
            'units' => $units,
            'unitConversionMap' => $unitConversionMap,
            'issue_date' => $issueDate,
            'invoice_number' => $invoiceNumber,
            'title' => 'Tambah Data Pembelian'
        ]);
    }

    public function store(Request $request)
    {
        $details = $request->input('details', []);
        $itemIds = [];

        foreach ($details as $detail) {
            if (in_array($detail['item_id'], $itemIds)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Barang yang sama tidak boleh diinput lebih dari satu kali dalam satu order.');
            }
            $itemIds[] = $detail['item_id'];
        }

        $totalAmount = 0;
        if (!empty($details)) {
            foreach ($details as $detail) {
                $totalAmount += $detail['subtotal'];
            }
        }

        $request->validate([
            'invoice_number' => 'required|unique:purchase_orders,invoice_number',
            'issue_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.unit_id' => 'required|exists:units,id',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.buy_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Insert purchase order
            $purchaseOrder = PurchaseOrder::create([
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'notes' => $request->notes,
                'supplier_id' => $request->supplier_id,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Insert purchase order items and update stock
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $itemId = $detail['item_id'];
                    $unitId = $detail['unit_id'];

                    $conversionRow = DB::table('unit_conversions')
                        ->where('item_id', $itemId)
                        ->where('unit_id', $unitId)
                        ->first();
                    $conversion = $conversionRow ? (int)$conversionRow->conversion_value : 1;

                    $quantity = (int)$detail['quantity'];
                    $baseQuantity = $quantity * $conversion;

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'item_id' => $itemId,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'base_quantity' => $baseQuantity,
                        'buy_price' => $detail['buy_price'],
                        'subtotal' => $detail['subtotal']
                    ]);

                    // Update item stock (increase)
                    DB::table('items')
                        ->where('id', $itemId)
                        ->increment('stock', $baseQuantity);
                }
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Menambah pembelian: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Data pembelian berhasil dibuat dan disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating purchase order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'purchaseOrderItems.item', 'purchaseOrderItems.unit'])
            ->findOrFail($id);

        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        $items = Item::orderBy('item_name')->get();
        $units = Unit::orderBy('unit_name')->get();

        // Unit conversion mapping
        $unitConversionMap = [];
        foreach ($items as $item) {
            $conversions = DB::table('unit_conversions')
                ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
                ->select('unit_conversions.*', 'units.unit_name as unit_name')
                ->where('unit_conversions.item_id', $item->id)
                ->get();
            $unitConversionMap[$item->id] = $conversions;
        }

        return view('purchase_orders.edit', [
            'id' => $id,
            'purchaseOrder' => $purchaseOrder,
            'details' => $purchaseOrder->purchaseOrderItems,
            'suppliers' => $suppliers,
            'items' => $items,
            'units' => $units,
            'unitConversionMap' => $unitConversionMap,
            'title' => 'Edit Data Pembelian'
        ]);
    }

    public function update(Request $request, $id)
    {
        $details = $request->input('details', []);
        $itemIds = [];
        foreach ($details as $detail) {
            if (in_array($detail['item_id'], $itemIds)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Barang yang sama tidak boleh diinput lebih dari satu kali dalam satu order.');
            }
            $itemIds[] = $detail['item_id'];
        }

        if (empty($details)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Detail barang tidak boleh kosong!');
        }

        $purchaseOrder = PurchaseOrder::with('purchaseOrderItems')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert old stock changes (subtract from stock)
            foreach ($purchaseOrder->purchaseOrderItems as $oldItem) {
                DB::table('items')
                    ->where('id', $oldItem->item_id)
                    ->decrement('stock', $oldItem->base_quantity);
            }

            // Delete old items
            $purchaseOrder->purchaseOrderItems()->delete();

            // Insert new items and update stock
            $totalAmount = 0;
            foreach ($details as $detail) {
                $itemId = $detail['item_id'];
                $unitId = $detail['unit_id'];

                $conversionRow = DB::table('unit_conversions')
                    ->where('item_id', $itemId)
                    ->where('unit_id', $unitId)
                    ->first();
                $conversion = $conversionRow ? (int)$conversionRow->conversion_value : 1;

                $quantity = (int)$detail['quantity'];
                $baseQuantity = $quantity * $conversion;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $id,
                    'item_id' => $itemId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'buy_price' => $detail['buy_price'],
                    'subtotal' => $detail['subtotal']
                ]);

                // Update item stock (increase)
                DB::table('items')
                    ->where('id', $itemId)
                    ->increment('stock', $baseQuantity);

                $totalAmount += $detail['subtotal'];
            }

            // Update purchase order
            $purchaseOrder->update([
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'supplier_id' => $request->supplier_id,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'updated_by' => auth()->id(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Mengupdate pembelian: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Data pembelian berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating purchase order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::with('purchaseOrderItems')->findOrFail($id);

        // Validate stock for all items first
        foreach ($purchaseOrder->purchaseOrderItems as $item) {
            $currentItem = Item::find($item->item_id);
            if ($currentItem->stock < $item->base_quantity) {
                return redirect()->route('purchases.index')
                    ->with('error', 'Stok barang tidak mencukupi untuk menghapus pembelian! (Stok saat ini: ' . $currentItem->stock . ', akan dikurangi sebesar: ' . $item->base_quantity . ')');
            }
        }

        DB::beginTransaction();
        try {
            // Subtract stock (reverse the original purchase)
            foreach ($purchaseOrder->purchaseOrderItems as $item) {
                DB::table('items')
                    ->where('id', $item->item_id)
                    ->decrement('stock', $item->base_quantity);
            }

            // Delete items and purchase order
            $purchaseOrder->purchaseOrderItems()->delete();
            $purchaseOrder->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Menghapus pembelian: ' . $purchaseOrder->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Data pembelian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting purchase order: ' . $e->getMessage());
            return redirect()->route('purchases.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Generate invoice number
    private function generateInvoiceNumber($issueDate)
    {
        $year = date('Y', strtotime($issueDate));
        $month = date('m', strtotime($issueDate));

        $invoices = PurchaseOrder::select('invoice_number')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->get();

        $maxSequence = 0;
        foreach ($invoices as $row) {
            if (preg_match('/PB\/' . $year . '\/(\d{4})\/' . $month . '/', $row->invoice_number, $match)) {
                $sequence = (int)$match[1];
                if ($sequence > $maxSequence) $maxSequence = $sequence;
            }
        }
        $nextSequence = str_pad($maxSequence + 1, 4, '0', STR_PAD_LEFT);

        return "PB/$year/$nextSequence/$month";
    }

    // AJAX untuk generate nomor faktur
    public function generateInvoiceNumberAjax(Request $request)
    {
        $issueDate = $request->get('issue_date');
        $invoiceNumber = $this->generateInvoiceNumber($issueDate);
        return response()->json(['invoice_number' => $invoiceNumber]);
    }

    // Export Excel
    public function export(Request $request)
    {
        $type = $request->get('type', 'monthly');
        $query = PurchaseOrder::with(['supplier', 'purchaseOrderItems.item']);

        if ($type === 'daily') {
            $date = $request->get('issue_date') ?? date('Y-m-d');
            $query->whereDate('issue_date', $date);
            $filename = "Pembelian_Harian_" . $date . ".xlsx";
        } elseif ($type === 'monthly') {
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('issue_date', $month)
                ->whereYear('issue_date', $year);
            $filename = "Pembelian_Bulan_{$month}_{$year}.xlsx";
        } elseif ($type === 'yearly') {
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereYear('issue_date', $year);
            $filename = "Pembelian_Tahun_{$year}.xlsx";
        } else {
            // Default monthly
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('issue_date', $month)
                ->whereYear('issue_date', $year);
            $filename = "Pembelian_Bulan_{$month}_{$year}.xlsx";
        }

        $query->orderBy('issue_date', 'ASC');
        $purchases = $query->get();

        // Prepare data for Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor Nota');
        $sheet->setCellValue('C1', 'Tanggal Terbit');
        $sheet->setCellValue('D1', 'Supplier');
        $sheet->setCellValue('E1', 'Total Harga');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Metode Pembayaran');
        $sheet->setCellValue('H1', 'Nama Barang');
        $sheet->setCellValue('I1', 'Qty');
        $sheet->setCellValue('J1', 'Harga Beli');
        $sheet->setCellValue('K1', 'Subtotal');

        $row = 2;
        foreach ($purchases as $i => $purchase) {
            $details = $purchase->purchaseOrderItems;
            $detailCount = count($details);
            $startRow = $row;
            $endRow = $row + ($detailCount > 0 ? $detailCount - 1 : 0);

            // Merge cells A-G from startRow to endRow if there are details
            if ($detailCount > 0) {
                foreach (range('A', 'G') as $col) {
                    $sheet->mergeCells("{$col}{$startRow}:{$col}{$endRow}");
                    $sheet->getStyle("{$col}{$startRow}:{$col}{$endRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }
            }

            // Fill main data in startRow
            $sheet->setCellValue('A' . $startRow, $i + 1);
            $sheet->setCellValue('B' . $startRow, $purchase->invoice_number);
            $sheet->setCellValue('C' . $startRow, $purchase->issue_date);
            $sheet->setCellValue('D' . $startRow, $purchase->supplier->name ?? '-');
            $sheet->setCellValue('E' . $startRow, $purchase->total_amount);
            $sheet->setCellValue('F' . $startRow, $purchase->status);
            $sheet->setCellValue('G' . $startRow, $purchase->payment_method);

            // Fill item details in columns H-K
            if ($detailCount > 0) {
                foreach ($details as $detail) {
                    $sheet->setCellValue('H' . $row, $detail->item->item_name ?? '-');
                    $sheet->setCellValue('I' . $row, $detail->quantity);
                    $sheet->setCellValue('J' . $row, $detail->buy_price);
                    $sheet->setCellValue('K' . $row, $detail->subtotal);
                    $row++;
                }
            } else {
                $row++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
