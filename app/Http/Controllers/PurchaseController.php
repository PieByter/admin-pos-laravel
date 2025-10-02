<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PurchaseController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('pembelian', $permissions) || in_array('pembelian_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('pembelian', $permissions);
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

        $purchases = DB::table('purchases')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->select('purchases.*', 'suppliers.name as supplier_name')
            ->orderByRaw('MONTH(purchases.issue_date) DESC')
            ->orderByRaw('YEAR(purchases.issue_date) DESC')
            ->orderBy('purchases.id', 'DESC')
            ->get()
            ->toArray();

        // Get items and suppliers for mapping
        $suppliers = Supplier::all()->toArray();
        $items = Item::all()->toArray();
        $supplierMap = array_column($suppliers, 'name', 'id');
        $itemMap = array_column($items, 'name', 'id');

        foreach ($purchases as &$purchase) {
            $purchase->supplier_name = $supplierMap[$purchase->supplier_id] ?? '-';

            // Get purchase details
            $details = DB::table('purchase_details')
                ->leftJoin('units', 'units.id', '=', 'purchase_details.unit_id')
                ->select('purchase_details.*', 'units.name as unit_name')
                ->where('purchase_details.purchase_id', $purchase->id)
                ->get()
                ->toArray();

            foreach ($details as &$detail) {
                $detail->item_name = $itemMap[$detail->item_id] ?? '-';
            }
            $purchase->details = $details;

            // Get authorization users
            $authorizationIds = json_decode($purchase->authorization ?? '[]', true);
            $userList = User::whereIn('id', $authorizationIds)->get()->toArray();
            $usernames = array_column($userList, 'username', 'id');
            $purchase->authorization_str = implode(', ', array_map(function ($id) use ($usernames) {
                return $usernames[$id] ?? 'Unknown';
            }, $authorizationIds));
        }

        $data = array_merge($this->getPermissionData(), [
            'purchases' => $purchases,
            'title' => 'Daftar Pembelian'
        ]);

        return view('purchases.index', $data);
    }

    public function show($id)
    {
        $this->requireReadAccess();

        $purchase = DB::table('purchases')->where('id', $id)->first();
        if (!$purchase) {
            return redirect()->route('purchases.index')
                ->with('error', 'Data pembelian tidak ditemukan');
        }

        $supplier = Supplier::find($purchase->supplier_id);
        $details = DB::table('purchase_details')
            ->leftJoin('units', 'units.id', '=', 'purchase_details.unit_id')
            ->select('purchase_details.*', 'units.name as unit_name')
            ->where('purchase_id', $id)
            ->get()
            ->toArray();

        $items = Item::all()->toArray();
        $itemMap = array_column($items, 'name', 'id');

        $authorizationIds = json_decode($purchase->authorization ?? '[]', true);
        $userList = User::whereIn('id', $authorizationIds)->get()->toArray();
        $usernames = array_column($userList, 'username', 'id');
        $authorizationStr = implode(', ', array_map(function ($id) use ($usernames) {
            return $usernames[$id] ?? 'Unknown';
        }, $authorizationIds));

        return view('purchases.show', [
            'purchase' => $purchase,
            'supplier' => $supplier,
            'details' => $details,
            'item_map' => $itemMap,
            'authorization_str' => $authorizationStr,
            'can_write' => $this->canWrite(),
            'title' => 'Detail Pembelian'
        ]);
    }

    public function create()
    {
        $this->requireWriteAccess();

        $suppliers = Supplier::orderBy('name')->get()->toArray();
        $items = Item::orderBy('name')->get()->toArray();
        $units = Unit::orderBy('name')->get()->toArray();
        $users = User::orderBy('username')->get()->toArray();

        // Unit conversion mapping
        $unitConversionMap = [];
        foreach ($items as $item) {
            $conversions = DB::table('unit_conversions')
                ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
                ->select('unit_conversions.*', 'units.name as unit_name')
                ->where('unit_conversions.item_id', $item['id'])
                ->get()
                ->toArray();
            $unitConversionMap[$item['id']] = $conversions;
        }

        $issueDate = date('Y-m-d');
        $invoiceNumber = $this->generateInvoiceNumber($issueDate);

        return view('purchases.create', [
            'suppliers' => $suppliers,
            'items' => $items,
            'units' => $units,
            'unit_conversion_map' => $unitConversionMap,
            'users' => $users,
            'issue_date' => $issueDate,
            'invoice_number' => $invoiceNumber,
            'title' => 'Tambah Data Pembelian'
        ]);
    }

    public function store(Request $request)
    {
        $this->requireWriteAccess();

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

        // Handle authorization
        $authorization = $request->input('authorization', []);
        if (!is_array($authorization)) {
            $authorization = json_decode($authorization, true) ?? [];
        }
        $authorization = array_map('intval', $authorization);
        $currentUserId = (int)session('user_id');
        if ($currentUserId && !in_array($currentUserId, $authorization)) {
            $authorization[] = $currentUserId;
        }

        $request->validate([
            'invoice_number' => 'required|unique:purchases,invoice_number',
            'issue_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.unit_id' => 'required|exists:units,id',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.purchase_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Insert purchase
            $purchaseData = [
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'description' => $request->description,
                'authorization' => json_encode($authorization),
                'supplier_id' => $request->supplier_id,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method
            ];

            $purchaseId = DB::table('purchases')->insertGetId($purchaseData);

            // Insert purchase details and update stock
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $itemId = $detail['item_id'];
                    $unitId = $detail['unit_id'];

                    $conversionRow = DB::table('unit_conversions')
                        ->where('item_id', $itemId)
                        ->where('unit_id', $unitId)
                        ->first();
                    $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;

                    $quantity = (int)$detail['quantity'];
                    $baseQuantity = $quantity * $conversion;

                    DB::table('purchase_details')->insert([
                        'purchase_id' => $purchaseId,
                        'item_id' => $itemId,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'base_quantity' => $baseQuantity,
                        'purchase_price' => $detail['purchase_price'],
                        'subtotal' => $detail['subtotal']
                    ]);

                    // Update item stock
                    DB::table('items')
                        ->where('id', $itemId)
                        ->increment('stock', $baseQuantity);
                }
            }

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menambah pembelian: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Data pembelian berhasil dibuat dan disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating purchase: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->requireWriteAccess();

        $purchase = DB::table('purchases')->where('id', $id)->first();
        $details = DB::table('purchase_details')->where('purchase_id', $id)->get()->toArray();

        if (!$purchase) {
            return redirect()->route('purchases.index')
                ->with('error', 'Data pembelian tidak ditemukan!');
        }

        $suppliers = Supplier::orderBy('name')->get()->toArray();
        $items = Item::orderBy('name')->get()->toArray();
        $units = Unit::orderBy('name')->get()->toArray();
        $users = User::orderBy('username')->get()->toArray();

        // Unit conversion mapping
        $unitConversionMap = [];
        foreach ($items as $item) {
            $conversions = DB::table('unit_conversions')
                ->leftJoin('units', 'units.id', '=', 'unit_conversions.unit_id')
                ->select('unit_conversions.*', 'units.name as unit_name')
                ->where('unit_conversions.item_id', $item['id'])
                ->get()
                ->toArray();
            $unitConversionMap[$item['id']] = $conversions;
        }

        $authorization = [];
        if (!empty($purchase->authorization)) {
            $authorization = json_decode($purchase->authorization, true);
            if (!is_array($authorization)) $authorization = [];
            $authorization = array_values(array_map('intval', $authorization));
        }

        // Get authorization usernames
        $usernames = [];
        foreach ($authorization as $uid) {
            foreach ($users as $u) {
                if ($u['id'] == $uid) {
                    $usernames[] = $u['username'];
                    break;
                }
            }
        }
        $authorizationStr = implode(', ', $usernames);

        return view('purchases.edit', [
            'id' => $id,
            'purchase' => $purchase,
            'details' => $details,
            'suppliers' => $suppliers,
            'items' => $items,
            'units' => $units,
            'unit_conversion_map' => $unitConversionMap,
            'users' => $users,
            'authorization' => $authorization,
            'authorization_str' => $authorizationStr,
            'title' => 'Edit Data Pembelian'
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

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

        $purchase = DB::table('purchases')->where('id', $id)->first();
        $oldDetails = DB::table('purchase_details')->where('purchase_id', $id)->get();

        // Check if data changed
        $dataChanged = $this->isDataChanged($purchase, $request, $details);

        $authorization = $request->input('authorization', []);
        if (!is_array($authorization)) {
            $authorization = json_decode($authorization, true) ?? [];
        }
        $authorization = array_values(array_map('intval', $authorization));

        $currentUser = (int)session('user_id');
        if ($dataChanged && $currentUser && !in_array($currentUser, $authorization)) {
            $authorization[] = $currentUser;
        }

        DB::beginTransaction();
        try {
            // Revert old stock changes
            foreach ($oldDetails as $oldDetail) {
                $conversionRow = DB::table('unit_conversions')
                    ->where('item_id', $oldDetail->item_id)
                    ->where('unit_id', $oldDetail->unit_id)
                    ->first();
                $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;
                $baseQuantity = (int)$oldDetail->quantity * $conversion;

                DB::table('items')
                    ->where('id', $oldDetail->item_id)
                    ->decrement('stock', $baseQuantity);
            }

            // Delete old details
            DB::table('purchase_details')->where('purchase_id', $id)->delete();

            // Insert new details and update stock
            $totalAmount = 0;
            foreach ($details as $detail) {
                $itemId = $detail['item_id'];
                $unitId = $detail['unit_id'];

                $conversionRow = DB::table('unit_conversions')
                    ->where('item_id', $itemId)
                    ->where('unit_id', $unitId)
                    ->first();
                $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;

                $quantity = (int)$detail['quantity'];
                $baseQuantity = $quantity * $conversion;

                DB::table('purchase_details')->insert([
                    'purchase_id' => $id,
                    'item_id' => $itemId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'purchase_price' => $detail['purchase_price'],
                    'subtotal' => $detail['subtotal']
                ]);

                // Update item stock
                DB::table('items')
                    ->where('id', $itemId)
                    ->increment('stock', $baseQuantity);

                $totalAmount += $detail['subtotal'];
            }

            // Update purchase
            $purchaseData = [
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'supplier_id' => $request->supplier_id,
                'description' => $request->description,
                'authorization' => json_encode($authorization),
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method
            ];
            DB::table('purchases')->where('id', $id)->update($purchaseData);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate pembelian: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Data pembelian berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating purchase: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->requireWriteAccess();

        $purchase = DB::table('purchases')->where('id', $id)->first();
        $details = DB::table('purchase_details')->where('purchase_id', $id)->get();

        if (!$purchase) {
            return redirect()->route('purchases.index')
                ->with('error', 'Data pembelian tidak ditemukan');
        }

        // Validate stock for all items first
        foreach ($details as $detail) {
            $itemId = $detail->item_id;
            $unitId = $detail->unit_id;

            $conversionRow = DB::table('unit_conversions')
                ->where('item_id', $itemId)
                ->where('unit_id', $unitId)
                ->first();
            $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;

            $baseQuantity = (int)$detail->quantity * $conversion;

            $item = Item::find($itemId);
            if ($item->stock < $baseQuantity) {
                return redirect()->route('purchases.index')
                    ->with('error', 'Stok barang tidak mencukupi untuk menghapus pembelian! (Stok saat ini: ' . $item->stock . ', akan dikurangi sebesar: ' . $baseQuantity . ')');
            }
        }

        DB::beginTransaction();
        try {
            // If validation passes, execute stock reduction and delete data
            foreach ($details as $detail) {
                $itemId = $detail->item_id;
                $unitId = $detail->unit_id;

                $conversionRow = DB::table('unit_conversions')
                    ->where('item_id', $itemId)
                    ->where('unit_id', $unitId)
                    ->first();
                $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;

                $baseQuantity = (int)$detail->quantity * $conversion;

                DB::table('items')
                    ->where('id', $itemId)
                    ->decrement('stock', $baseQuantity);
            }

            // Delete details and purchase
            DB::table('purchase_details')->where('purchase_id', $id)->delete();
            DB::table('purchases')->where('id', $id)->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus pembelian: ' . ($purchase ? $purchase->invoice_number : 'ID ' . $id),
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Data pembelian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting purchase: ' . $e->getMessage());
            return redirect()->route('purchases.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Generate invoice number
    public function generateInvoiceNumber($issueDate)
    {
        $year = date('Y', strtotime($issueDate));
        $month = date('m', strtotime($issueDate));

        $invoices = DB::table('purchases')
            ->select('invoice_number')
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

    // Helper untuk cek apakah data berubah
    private function isDataChanged($purchase, $request, $newDetails)
    {
        $fieldsToCheck = [
            'invoice_number',
            'issue_date',
            'supplier_id',
            'description',
            'total_amount',
            'status',
            'payment_method'
        ];

        foreach ($fieldsToCheck as $field) {
            if ($purchase->$field != $request->input($field)) {
                return true;
            }
        }

        // Check if details changed
        $oldDetails = DB::table('purchase_details')->where('purchase_id', $purchase->id)->get();

        if (count($oldDetails) != count($newDetails)) {
            return true;
        }

        // Normalize and compare details
        $normalizeDetail = function ($detail) {
            return [
                'item_id' => (int)($detail['item_id'] ?? $detail->item_id ?? 0),
                'unit_id' => (int)($detail['unit_id'] ?? $detail->unit_id ?? 0),
                'quantity' => (float)($detail['quantity'] ?? $detail->quantity ?? 0),
                'purchase_price' => (float)($detail['purchase_price'] ?? $detail->purchase_price ?? 0),
                'subtotal' => (float)($detail['subtotal'] ?? $detail->subtotal ?? 0)
            ];
        };

        $newDetailsNorm = array_map($normalizeDetail, $newDetails);
        $oldDetailsNorm = $oldDetails->map($normalizeDetail)->toArray();

        usort($newDetailsNorm, function ($a, $b) {
            return $a['item_id'] <=> $b['item_id'];
        });
        usort($oldDetailsNorm, function ($a, $b) {
            return $a['item_id'] <=> $b['item_id'];
        });

        return $newDetailsNorm != $oldDetailsNorm;
    }

    // Export Excel
    public function export(Request $request)
    {
        $this->requireReadAccess();

        $type = $request->get('type', 'monthly');
        $query = DB::table('purchases')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->select('purchases.*', 'suppliers.name as supplier_name');

        if ($type === 'daily') {
            $date = $request->get('issue_date') ?? date('Y-m-d');
            $query->whereDate('purchases.issue_date', $date);
            $filename = "Pembelian_Harian_" . $date . ".xlsx";
        } elseif ($type === 'monthly') {
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('purchases.issue_date', $month)
                ->whereYear('purchases.issue_date', $year);
            $filename = "Pembelian_Bulan_{$month}_{$year}.xlsx";
        } elseif ($type === 'yearly') {
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereYear('purchases.issue_date', $year);
            $filename = "Pembelian_Tahun_{$year}.xlsx";
        } else {
            // Default monthly
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('purchases.issue_date', $month)
                ->whereYear('purchases.issue_date', $year);
            $filename = "Pembelian_Bulan_{$month}_{$year}.xlsx";
        }

        $query->orderBy('purchases.issue_date', 'ASC');
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
            // Get item details
            $details = DB::table('purchase_details')
                ->leftJoin('items', 'items.id', '=', 'purchase_details.item_id')
                ->select('purchase_details.*', 'items.name as item_name')
                ->where('purchase_id', $purchase->id)
                ->get();

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
            $sheet->setCellValue('D' . $startRow, $purchase->supplier_name);
            $sheet->setCellValue('E' . $startRow, $purchase->total_amount);
            $sheet->setCellValue('F' . $startRow, $purchase->status);
            $sheet->setCellValue('G' . $startRow, $purchase->payment_method);

            // Fill item details in columns H-K
            if ($detailCount > 0) {
                foreach ($details as $detail) {
                    $sheet->setCellValue('H' . $row, $detail->item_name);
                    $sheet->setCellValue('I' . $row, $detail->quantity);
                    $sheet->setCellValue('J' . $row, $detail->purchase_price);
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