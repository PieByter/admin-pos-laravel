<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SalesController extends Controller
{
    public function index()
    {
        $sales = SalesOrder::with(['customer', 'salesOrderItems.item', 'salesOrderItems.unit', 'createdBy', 'updatedBy'])
            ->orderByRaw('MONTH(issue_date) DESC')
            ->orderByRaw('YEAR(issue_date) DESC')
            ->orderBy('id', 'DESC')
            ->get();

        return view('sales_orders.index', [
            'sales' => $sales,
            'title' => 'Daftar Penjualan'
        ]);
    }

    public function show($id)
    {
        $salesOrder = SalesOrder::with(['customer', 'salesOrderItems.item', 'salesOrderItems.unit', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        return view('sales_orders.show', [
            'salesOrder' => $salesOrder,
            'customer' => $salesOrder->customer,
            'details' => $salesOrder->salesOrderItems,
            'title' => 'Detail Penjualan'
        ]);
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $items = Item::orderBy('item_name')->get();
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

        $issueDate = date('Y-m-d');
        $invoiceNumber = $this->generateInvoiceNumber($issueDate);

        return view('sales_orders.create', [
            'customers' => $customers,
            'items' => $items,
            'units' => $units,
            'unitConversionMap' => $unitConversionMap,
            'issue_date' => $issueDate,
            'invoice_number' => $invoiceNumber,
            'title' => 'Tambah Data Penjualan'
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

        // Validate stock availability
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

                $item = Item::find($itemId);
                $stock = $item ? (int)$item->stock : 0;
                if ($baseQuantity > $stock) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Qty barang "' . ($item->name ?? 'Unknown') . '" melebihi stok tersedia!');
                }
            }
        }

        $totalAmount = 0;
        if (!empty($details)) {
            foreach ($details as $detail) {
                $totalAmount += $detail['subtotal'];
            }
        }

        $request->validate([
            'invoice_number' => 'required|unique:sales_orders,invoice_number',
            'issue_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.unit_id' => 'required|exists:units,id',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.sell_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Insert sales order
            $salesOrder = SalesOrder::create([
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'notes' => $request->notes,
                'customer_id' => $request->customer_id,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Insert sales order items and update stock
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

                    SalesOrderItem::create([
                        'sales_order_id' => $salesOrder->id,
                        'item_id' => $itemId,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'base_quantity' => $baseQuantity,
                        'sell_price' => $detail['sell_price'],
                        'subtotal' => $detail['subtotal']
                    ]);

                    // Update item stock (decrease)
                    DB::table('items')
                        ->where('id', $itemId)
                        ->decrement('stock', $baseQuantity);
                }
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Menambah penjualan: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil dibuat dan disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating sales order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $salesOrder = SalesOrder::with([
            'customer',
            'salesOrderItems.item',
            'salesOrderItems.unit',
            'createdBy',
            'updatedBy'
        ])->findOrFail($id);

        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $items = Item::orderBy('item_name')->get();
        $units = Unit::orderBy('unit_name')->get();

        // Unit conversion mapping
        // $unitConversionMap = [];
        // foreach ($items as $item) {
        //     $conversions = DB::table('unit_conversions')
        //         ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
        //         ->select('unit_conversions.*', 'units.unit_name as unit_name')
        //         ->where('unit_conversions.item_id', $item->id)
        //         ->get();
        //     $unitConversionMap[$item->id] = $conversions;
        // }

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


        return view('sales_orders.edit', [
            'id' => $id,
            'salesOrder' => $salesOrder,
            'details' => $salesOrder->salesOrderItems,
            'customers' => $customers,
            'items' => $items,
            'units' => $units,
            'unit_conversion_map' => $unitConversionMap,
            'title' => 'Edit Data Penjualan'
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

        $salesOrder = SalesOrder::with('salesOrderItems')->findOrFail($id);

        // Validate stock availability for new quantities
        foreach ($details as $detail) {
            $itemId = $detail['item_id'];
            $unitId = $detail['unit_id'];

            $item = Item::find($itemId);
            $currentStock = $item ? (int)$item->stock : 0;

            $conversionRow = DB::table('unit_conversions')
                ->where('item_id', $itemId)
                ->where('unit_id', $unitId)
                ->first();
            $newConversion = $conversionRow ? (int)$conversionRow->conversion_value : 1;

            $newQuantity = (int)$detail['quantity'];
            $newBaseQuantity = $newQuantity * $newConversion;

            // Find old quantity for this item
            $oldBaseQuantity = 0;
            foreach ($salesOrder->salesOrderItems as $oldDetail) {
                if ($oldDetail->item_id == $itemId) {
                    $oldBaseQuantity = $oldDetail->base_quantity;
                    break;
                }
            }

            $availableStock = $currentStock + $oldBaseQuantity;

            if ($newBaseQuantity > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Qty barang "' . ($item->name ?? 'Unknown') . '" melebihi stok tersedia!');
            }
        }

        DB::beginTransaction();
        try {
            // Revert old stock changes (add back)
            foreach ($salesOrder->salesOrderItems as $oldItem) {
                DB::table('items')
                    ->where('id', $oldItem->item_id)
                    ->increment('stock', $oldItem->base_quantity);
            }

            // Delete old items
            $salesOrder->salesOrderItems()->delete();

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

                SalesOrderItem::create([
                    'sales_order_id' => $id,
                    'item_id' => $itemId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'sell_price' => $detail['sell_price'],
                    'subtotal' => $detail['subtotal']
                ]);

                // Update item stock (decrease)
                DB::table('items')
                    ->where('id', $itemId)
                    ->decrement('stock', $baseQuantity);

                $totalAmount += $detail['subtotal'];
            }

            // Update sales order
            $salesOrder->update([
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'customer_id' => $request->customer_id,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'updated_by' => auth()->id(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Mengupdate penjualan: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating sales order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $salesOrder = SalesOrder::with('salesOrderItems')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Return stock to inventory
            foreach ($salesOrder->salesOrderItems as $item) {
                DB::table('items')
                    ->where('id', $item->item_id)
                    ->increment('stock', $item->base_quantity);
            }

            // Delete items and sales order
            $salesOrder->salesOrderItems()->delete();
            $salesOrder->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Menghapus penjualan: ' . $salesOrder->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting sales order: ' . $e->getMessage());
            return redirect()->route('sales.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Generate invoice number
    private function generateInvoiceNumber($issueDate)
    {
        $year = date('Y', strtotime($issueDate));
        $month = date('m', strtotime($issueDate));

        $invoices = SalesOrder::select('invoice_number')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->get();

        $maxSequence = 0;
        foreach ($invoices as $row) {
            if (preg_match('/PJ\/' . $year . '\/(\d{4})\/' . $month . '/', $row->invoice_number, $match)) {
                $sequence = (int)$match[1];
                if ($sequence > $maxSequence) $maxSequence = $sequence;
            }
        }
        $nextSequence = str_pad($maxSequence + 1, 4, '0', STR_PAD_LEFT);

        return "PJ/$year/$nextSequence/$month";
    }

    // AJAX untuk generate nomor invoice
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
        $query = SalesOrder::with(['customer', 'salesOrderItems.item']);

        if ($type === 'daily') {
            $date = $request->get('issue_date') ?? date('Y-m-d');
            $query->whereDate('issue_date', $date);
            $filename = "Penjualan_Harian_" . $date . ".xlsx";
        } elseif ($type === 'monthly') {
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('issue_date', $month)
                ->whereYear('issue_date', $year);
            $filename = "Penjualan_Bulan_{$month}_{$year}.xlsx";
        } elseif ($type === 'yearly') {
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereYear('issue_date', $year);
            $filename = "Penjualan_Tahun_{$year}.xlsx";
        } else {
            // Default monthly
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('issue_date', $month)
                ->whereYear('issue_date', $year);
            $filename = "Penjualan_Bulan_{$month}_{$year}.xlsx";
        }

        $query->orderBy('issue_date', 'ASC');
        $sales = $query->get();

        // Prepare data for Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor Faktur');
        $sheet->setCellValue('C1', 'Tanggal Terbit');
        $sheet->setCellValue('D1', 'Customer');
        $sheet->setCellValue('E1', 'Total Harga');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Metode Pembayaran');
        $sheet->setCellValue('H1', 'Nama Barang');
        $sheet->setCellValue('I1', 'Qty');
        $sheet->setCellValue('J1', 'Harga Jual');
        $sheet->setCellValue('K1', 'Subtotal');

        $row = 2;
        foreach ($sales as $i => $sale) {
            $details = $sale->salesOrderItems;
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
            $sheet->setCellValue('B' . $startRow, $sale->invoice_number);
            $sheet->setCellValue('C' . $startRow, $sale->issue_date);
            $sheet->setCellValue('D' . $startRow, $sale->customer->name ?? '-');
            $sheet->setCellValue('E' . $startRow, $sale->total_amount);
            $sheet->setCellValue('F' . $startRow, $sale->status);
            $sheet->setCellValue('G' . $startRow, $sale->payment_method);

            // Fill item details in columns H-K
            if ($detailCount > 0) {
                foreach ($details as $detail) {
                    $sheet->setCellValue('H' . $row, $detail->item->name ?? '-');
                    $sheet->setCellValue('I' . $row, $detail->quantity);
                    $sheet->setCellValue('J' . $row, $detail->sell_price);
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