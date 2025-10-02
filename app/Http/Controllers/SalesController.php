<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SalesController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('penjualan', $permissions) || in_array('penjualan_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('penjualan', $permissions);
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

        $sales = DB::table('sales')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->select('sales.*', 'customers.name as customer_name')
            ->orderByRaw('MONTH(sales.issue_date) DESC')
            ->orderByRaw('YEAR(sales.issue_date) DESC')
            ->orderBy('sales.id', 'DESC')
            ->get()
            ->toArray();

        // Get customers and items for mapping
        $customers = Customer::all()->toArray();
        $items = Item::all()->toArray();
        $customerMap = array_column($customers, 'name', 'id');
        $itemMap = array_column($items, 'name', 'id');

        foreach ($sales as &$sale) {
            $sale->customer_name = $customerMap[$sale->customer_id] ?? '-';

            // Get sale details
            $details = DB::table('sale_details')
                ->leftJoin('units', 'units.id', '=', 'sale_details.unit_id')
                ->select('sale_details.*', 'units.name as unit_name')
                ->where('sale_details.sale_id', $sale->id)
                ->get()
                ->toArray();

            foreach ($details as &$detail) {
                $detail->item_name = $itemMap[$detail->item_id] ?? '-';
            }
            $sale->details = $details;

            // Get authorization users
            $authorizationIds = json_decode($sale->authorization ?? '[]', true);
            $userList = User::whereIn('id', $authorizationIds)->get()->toArray();
            $usernames = array_column($userList, 'username', 'id');
            $sale->authorization_str = implode(', ', array_map(function ($id) use ($usernames) {
                return $usernames[$id] ?? 'Unknown';
            }, $authorizationIds));
        }

        $data = array_merge($this->getPermissionData(), [
            'sales' => $sales,
            'title' => 'Daftar Penjualan'
        ]);

        return view('sales.index', $data);
    }

    public function show($id)
    {
        $this->requireReadAccess();

        $sale = DB::table('sales')->where('id', $id)->first();
        if (!$sale) {
            return redirect()->route('sales.index')
                ->with('error', 'Data penjualan tidak ditemukan');
        }

        $customer = Customer::find($sale->customer_id);
        $details = DB::table('sale_details')
            ->leftJoin('units', 'units.id', '=', 'sale_details.unit_id')
            ->select('sale_details.*', 'units.name as unit_name')
            ->where('sale_id', $id)
            ->get()
            ->toArray();

        $items = Item::all()->toArray();
        $itemMap = array_column($items, 'name', 'id');

        $authorizationIds = json_decode($sale->authorization ?? '[]', true);
        $userList = User::whereIn('id', $authorizationIds)->get()->toArray();
        $usernames = array_column($userList, 'username', 'id');
        $authorizationStr = implode(', ', array_map(function ($id) use ($usernames) {
            return $usernames[$id] ?? 'Unknown';
        }, $authorizationIds));

        return view('sales.show', [
            'sale' => $sale,
            'customer' => $customer,
            'details' => $details,
            'item_map' => $itemMap,
            'authorization_str' => $authorizationStr,
            'can_write' => $this->canWrite(),
            'title' => 'Detail Penjualan'
        ]);
    }

    public function create()
    {
        $this->requireWriteAccess();

        $customers = Customer::orderBy('name')->get()->toArray();
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

        return view('sales.create', [
            'customers' => $customers,
            'items' => $items,
            'units' => $units,
            'unit_conversion_map' => $unitConversionMap,
            'users' => $users,
            'issue_date' => $issueDate,
            'invoice_number' => $invoiceNumber,
            'title' => 'Tambah Data Penjualan'
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

        // Validate stock availability
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
            'invoice_number' => 'required|unique:sales,invoice_number',
            'issue_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.unit_id' => 'required|exists:units,id',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.sale_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Insert sale
            $saleData = [
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'description' => $request->description,
                'authorization' => json_encode($authorization),
                'customer_id' => $request->customer_id,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method
            ];

            $saleId = DB::table('sales')->insertGetId($saleData);

            // Insert sale details and update stock
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

                    DB::table('sale_details')->insert([
                        'sale_id' => $saleId,
                        'item_id' => $itemId,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'base_quantity' => $baseQuantity,
                        'sale_price' => $detail['sale_price'],
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
                'user_id' => session('user_id'),
                'activity' => 'Menambah penjualan: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil dibuat dan disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating sale: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->requireWriteAccess();

        $sale = DB::table('sales')->where('id', $id)->first();
        $details = DB::table('sale_details')->where('sale_id', $id)->get()->toArray();

        if (!$sale) {
            return redirect()->route('sales.index')
                ->with('error', 'Data penjualan tidak ditemukan!');
        }

        $customers = Customer::orderBy('name')->get()->toArray();
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
        if (!empty($sale->authorization)) {
            $authorization = json_decode($sale->authorization, true);
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

        return view('sales.edit', [
            'id' => $id,
            'sale' => $sale,
            'details' => $details,
            'customers' => $customers,
            'items' => $items,
            'units' => $units,
            'unit_conversion_map' => $unitConversionMap,
            'users' => $users,
            'authorization' => $authorization,
            'authorization_str' => $authorizationStr,
            'title' => 'Edit Data Penjualan'
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

        $sale = DB::table('sales')->where('id', $id)->first();
        $oldDetails = DB::table('sale_details')->where('sale_id', $id)->get();

        // Check if data changed
        $dataChanged = $this->isDataChanged($sale, $request, $details);

        $authorization = $request->input('authorization', []);
        if (!is_array($authorization)) {
            $authorization = json_decode($authorization, true) ?? [];
        }
        $authorization = array_values(array_map('intval', $authorization));

        $currentUser = (int)session('user_id');
        if ($dataChanged && $currentUser && !in_array($currentUser, $authorization)) {
            $authorization[] = $currentUser;
        }

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
            $newConversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;

            $newQuantity = (int)$detail['quantity'];
            $newBaseQuantity = $newQuantity * $newConversion;

            // Find old quantity for this item
            $oldBaseQuantity = 0;
            foreach ($oldDetails as $oldDetail) {
                if ($oldDetail->item_id == $itemId) {
                    $oldConversionRow = DB::table('unit_conversions')
                        ->where('item_id', $oldDetail->item_id)
                        ->where('unit_id', $oldDetail->unit_id)
                        ->first();
                    $oldConversion = $oldConversionRow ? (int)$oldConversionRow->conversion_rate : 1;
                    $oldBaseQuantity = (int)$oldDetail->quantity * $oldConversion;
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
            foreach ($oldDetails as $oldDetail) {
                $conversionRow = DB::table('unit_conversions')
                    ->where('item_id', $oldDetail->item_id)
                    ->where('unit_id', $oldDetail->unit_id)
                    ->first();
                $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;
                $baseQuantity = (int)$oldDetail->quantity * $conversion;

                DB::table('items')
                    ->where('id', $oldDetail->item_id)
                    ->increment('stock', $baseQuantity);
            }

            // Delete old details
            DB::table('sale_details')->where('sale_id', $id)->delete();

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

                DB::table('sale_details')->insert([
                    'sale_id' => $id,
                    'item_id' => $itemId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'sale_price' => $detail['sale_price'],
                    'subtotal' => $detail['subtotal']
                ]);

                // Update item stock (decrease)
                DB::table('items')
                    ->where('id', $itemId)
                    ->decrement('stock', $baseQuantity);

                $totalAmount += $detail['subtotal'];
            }

            // Update sale
            $saleData = [
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'customer_id' => $request->customer_id,
                'description' => $request->description,
                'authorization' => json_encode($authorization),
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method
            ];
            DB::table('sales')->where('id', $id)->update($saleData);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate penjualan: ' . $request->invoice_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating sale: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->requireWriteAccess();

        $sale = DB::table('sales')->where('id', $id)->first();
        $details = DB::table('sale_details')->where('sale_id', $id)->get();

        if (!$sale) {
            return redirect()->route('sales.index')
                ->with('error', 'Data penjualan tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            // Return stock to inventory
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
                    ->increment('stock', $baseQuantity);
            }

            // Delete details and sale
            DB::table('sale_details')->where('sale_id', $id)->delete();
            DB::table('sales')->where('id', $id)->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus penjualan: ' . ($sale ? $sale->invoice_number : 'ID ' . $id),
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting sale: ' . $e->getMessage());
            return redirect()->route('sales.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Generate invoice number
    private function generateInvoiceNumber($issueDate)
    {
        $year = date('Y', strtotime($issueDate));
        $month = date('m', strtotime($issueDate));

        $invoices = DB::table('sales')
            ->select('invoice_number')
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

    // Helper untuk cek apakah data berubah
    private function isDataChanged($sale, $request, $newDetails)
    {
        $fieldsToCheck = [
            'invoice_number',
            'issue_date',
            'customer_id',
            'description',
            'total_amount',
            'status',
            'payment_method'
        ];

        foreach ($fieldsToCheck as $field) {
            if ($sale->$field != $request->input($field)) {
                return true;
            }
        }

        // Check if details changed
        $oldDetails = DB::table('sale_details')->where('sale_id', $sale->id)->get();

        if (count($oldDetails) != count($newDetails)) {
            return true;
        }

        // Normalize and compare details
        $normalizeDetail = function ($detail) {
            return [
                'item_id' => (int)($detail['item_id'] ?? $detail->item_id ?? 0),
                'unit_id' => (int)($detail['unit_id'] ?? $detail->unit_id ?? 0),
                'quantity' => (float)($detail['quantity'] ?? $detail->quantity ?? 0),
                'sale_price' => (float)($detail['sale_price'] ?? $detail->sale_price ?? 0),
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
        $query = DB::table('sales')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->select('sales.*', 'customers.name as customer_name');

        if ($type === 'daily') {
            $date = $request->get('issue_date') ?? date('Y-m-d');
            $query->whereDate('sales.issue_date', $date);
            $filename = "Penjualan_Harian_" . $date . ".xlsx";
        } elseif ($type === 'monthly') {
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('sales.issue_date', $month)
                ->whereYear('sales.issue_date', $year);
            $filename = "Penjualan_Bulan_{$month}_{$year}.xlsx";
        } elseif ($type === 'yearly') {
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereYear('sales.issue_date', $year);
            $filename = "Penjualan_Tahun_{$year}.xlsx";
        } else {
            // Default monthly
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('sales.issue_date', $month)
                ->whereYear('sales.issue_date', $year);
            $filename = "Penjualan_Bulan_{$month}_{$year}.xlsx";
        }

        $query->orderBy('sales.issue_date', 'ASC');
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
            // Get item details
            $details = DB::table('sale_details')
                ->leftJoin('items', 'items.id', '=', 'sale_details.item_id')
                ->select('sale_details.*', 'items.name as item_name')
                ->where('sale_id', $sale->id)
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
            $sheet->setCellValue('B' . $startRow, $sale->invoice_number);
            $sheet->setCellValue('C' . $startRow, $sale->issue_date);
            $sheet->setCellValue('D' . $startRow, $sale->customer_name);
            $sheet->setCellValue('E' . $startRow, $sale->total_amount);
            $sheet->setCellValue('F' . $startRow, $sale->status);
            $sheet->setCellValue('G' . $startRow, $sale->payment_method);

            // Fill item details in columns H-K
            if ($detailCount > 0) {
                foreach ($details as $detail) {
                    $sheet->setCellValue('H' . $row, $detail->item_name);
                    $sheet->setCellValue('I' . $row, $detail->quantity);
                    $sheet->setCellValue('J' . $row, $detail->sale_price);
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