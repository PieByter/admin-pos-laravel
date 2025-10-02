<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PrePurchaseOrder;
use App\Models\PrePurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\User;
use App\Models\Unit;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class PrePurchaseController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('purchase_order', $permissions) || in_array('purchase_order_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('purchase_order', $permissions);
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

        $preOrders = DB::table('pre_purchase_orders')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'pre_purchase_orders.supplier_id')
            ->select('pre_purchase_orders.*', 'suppliers.name as supplier_name')
            ->orderByRaw('MONTH(pre_purchase_orders.issue_date) DESC')
            ->orderByRaw('YEAR(pre_purchase_orders.issue_date) DESC')
            ->orderBy('pre_purchase_orders.id', 'DESC')
            ->get()
            ->toArray();

        // Get items and suppliers for mapping
        $suppliers = Supplier::all()->toArray();
        $items = Item::all()->toArray();
        $supplierMap = array_column($suppliers, 'name', 'id');
        $itemMap = array_column($items, 'name', 'id');

        foreach ($preOrders as &$po) {
            $po->supplier_name = $supplierMap[$po->supplier_id] ?? '-';

            // Get details
            $details = DB::table('pre_purchase_order_details')
                ->leftJoin('units', 'units.id', '=', 'pre_purchase_order_details.unit_id')
                ->select('pre_purchase_order_details.*', 'units.name as unit_name')
                ->where('pre_purchase_order_details.pre_purchase_order_id', $po->id)
                ->get()
                ->toArray();

            foreach ($details as &$detail) {
                $detail->item_name = $itemMap[$detail->item_id] ?? '-';
            }
            $po->details = $details;

            // Get authorization users
            $authorizationIds = json_decode($po->authorization ?? '[]', true);
            $userList = User::whereIn('id', $authorizationIds)->get()->toArray();
            $usernames = array_column($userList, 'username', 'id');
            $po->authorization_str = implode(', ', array_map(function ($id) use ($usernames) {
                return $usernames[$id] ?? 'Unknown';
            }, $authorizationIds));
        }

        $data = array_merge($this->getPermissionData(), [
            'pre_orders' => $preOrders,
            'title' => 'Daftar Pre Purchase Order'
        ]);

        return view('pre_purchase_orders.index', $data);
    }

    public function create()
    {
        $this->requireWriteAccess();

        $suppliers = Supplier::orderBy('name')->get()->toArray();
        $items = Item::orderBy('name')->get()->toArray();
        $users = User::orderBy('username')->get()->toArray();
        $units = Unit::orderBy('name')->get()->toArray();

        $issueDate = date('Y-m-d');
        $orderNumber = $this->generateOrderNumber($issueDate);

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

        return view('pre_purchase_orders.create', [
            'suppliers' => $suppliers,
            'items' => $items,
            'users' => $users,
            'units' => $units,
            'issue_date' => $issueDate,
            'order_number' => $orderNumber,
            'unit_conversion_map' => $unitConversionMap,
            'title' => 'Buat Pre Purchase Order Baru'
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

        // Validate due date
        $issueDate = $request->input('issue_date');
        $dueDate = $request->input('due_date');
        if ($dueDate < $issueDate) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jatuh tempo tidak boleh kurang dari tanggal terbit!');
        }

        DB::beginTransaction();
        try {
            // Insert pre purchase order
            $preOrderData = [
                'order_number' => $request->order_number,
                'issue_date' => $request->issue_date,
                'supplier_id' => $request->supplier_id,
                'description' => $request->description,
                'total_amount' => $request->total_amount,
                'tax' => $request->tax ?? 0,
                'due_date' => $request->due_date,
                'authorization' => json_encode($authorization),
                'status' => $request->status,
                'payment_method' => $request->payment_method,
            ];

            $preOrderId = DB::table('pre_purchase_orders')->insertGetId($preOrderData);

            // Insert details
            if (!empty($details)) {
                foreach ($details as $detail) {
                    DB::table('pre_purchase_order_details')->insert([
                        'pre_purchase_order_id' => $preOrderId,
                        'item_id' => $detail['item_id'],
                        'unit_id' => $detail['unit_id'],
                        'quantity' => $detail['quantity'],
                        'price' => $detail['price'],
                        'subtotal' => $detail['subtotal']
                    ]);
                }
            }

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => session('user_id'),
                'activity' => 'Menambah Pre Purchase Order: ' . $request->order_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('pre-purchase-orders.index')
                ->with('success', 'Pre Purchase Order berhasil dibuat dan disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating pre purchase order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->requireWriteAccess();

        $preOrder = DB::table('pre_purchase_orders')->where('id', $id)->first();
        $details = DB::table('pre_purchase_order_details')->where('pre_purchase_order_id', $id)->get()->toArray();

        if (!$preOrder) {
            return redirect()->route('pre-purchase-orders.index')
                ->with('error', 'Pre Purchase Order tidak ditemukan!');
        }

        $suppliers = Supplier::orderBy('name')->get()->toArray();
        $items = Item::orderBy('name')->get()->toArray();
        $users = User::orderBy('username')->get()->toArray();
        $units = Unit::orderBy('name')->get()->toArray();

        $authorization = [];
        if (!empty($preOrder->authorization)) {
            $authorization = json_decode($preOrder->authorization, true);
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

        $data = [
            'id' => $id,
            'order_number' => $preOrder->order_number,
            'issue_date' => $preOrder->issue_date,
            'supplier_id' => $preOrder->supplier_id,
            'description' => $preOrder->description,
            'total_amount' => $preOrder->total_amount,
            'tax' => $preOrder->tax,
            'due_date' => $preOrder->due_date,
            'authorization' => $authorization,
            'authorization_str' => $authorizationStr,
            'status' => $preOrder->status,
            'payment_method' => $preOrder->payment_method,
            'suppliers' => $suppliers,
            'items' => $items,
            'users' => $users,
            'details' => $details,
            'units' => $units,
            'unit_conversion_map' => $unitConversionMap,
            'title' => 'Edit Pre Purchase Order'
        ];

        return view('pre_purchase_orders.edit', $data);
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

        $preOrder = DB::table('pre_purchase_orders')->where('id', $id)->first();

        $authorization = $request->input('authorization', []);
        if (!is_array($authorization)) {
            $authorization = json_decode($authorization, true) ?? [];
        }
        $authorization = array_map('intval', $authorization);

        // Check if data changed
        $dataChanged = $this->isDataChanged($preOrder, $request, $details);
        $currentUserId = (int)session('user_id');
        if ($dataChanged && $currentUserId && !in_array($currentUserId, $authorization)) {
            $authorization[] = $currentUserId;
        }

        // Validate due date
        $issueDate = $request->input('issue_date');
        $dueDate = $request->input('due_date');
        if ($dueDate < $issueDate) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jatuh tempo tidak boleh kurang dari tanggal terbit!');
        }

        DB::beginTransaction();
        try {
            // Update pre purchase order
            $preOrderData = [
                'order_number' => $request->order_number,
                'issue_date' => $request->issue_date,
                'supplier_id' => $request->supplier_id,
                'description' => $request->description,
                'total_amount' => $request->total_amount,
                'tax' => $request->tax ?? 0,
                'due_date' => $request->due_date,
                'authorization' => json_encode($authorization),
                'status' => $request->status,
                'payment_method' => $request->payment_method,
            ];

            DB::table('pre_purchase_orders')->where('id', $id)->update($preOrderData);

            // Delete old details and insert new ones
            DB::table('pre_purchase_order_details')->where('pre_purchase_order_id', $id)->delete();

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

                    DB::table('pre_purchase_order_details')->insert([
                        'pre_purchase_order_id' => $id,
                        'item_id' => $itemId,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'base_quantity' => $baseQuantity,
                        'price' => $detail['price'],
                        'subtotal' => $detail['subtotal']
                    ]);
                }
            }

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => session('user_id'),
                'activity' => 'Mengupdate Pre Purchase Order: ' . $request->order_number,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('pre-purchase-orders.index')
                ->with('success', 'Pre Purchase Order berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating pre purchase order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->requireWriteAccess();

        $preOrder = DB::table('pre_purchase_orders')->where('id', $id)->first();
        if (!$preOrder) {
            return redirect()->route('pre-purchase-orders.index')
                ->with('error', 'Pre Purchase Order tidak ditemukan');
        }

        try {
            DB::beginTransaction();

            // Delete details first
            DB::table('pre_purchase_order_details')->where('pre_purchase_order_id', $id)->delete();

            // Delete pre purchase order
            $orderNumber = $preOrder->order_number;
            DB::table('pre_purchase_orders')->where('id', $id)->delete();

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => session('user_id'),
                'activity' => 'Menghapus Pre Purchase Order: ' . $orderNumber,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('pre-purchase-orders.index')
                ->with('success', 'Pre Purchase Order berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting pre purchase order: ' . $e->getMessage());
            return redirect()->route('pre-purchase-orders.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Generate nomor Pre Purchase Order
    private function generateOrderNumber($issueDate)
    {
        $year = date('Y', strtotime($issueDate));
        $month = date('m', strtotime($issueDate));

        $count = DB::table('pre_purchase_orders')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->count();

        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return "PPO/$year/$sequence/$month";
    }

    // AJAX untuk generate nomor Pre PO
    public function generateOrderNumberAjax(Request $request)
    {
        $issueDate = $request->get('issue_date');
        $orderNumber = $this->generateOrderNumber($issueDate);
        return response()->json(['order_number' => $orderNumber]);
    }

    public function show($id)
    {
        $this->requireReadAccess();

        $preOrder = DB::table('pre_purchase_orders')->where('id', $id)->first();
        if (!$preOrder) {
            return redirect()->route('pre-purchase-orders.index')
                ->with('error', 'Pre Purchase Order tidak ditemukan');
        }

        $authorizationIds = [];
        if (!empty($preOrder->authorization)) {
            $authorizationIds = json_decode($preOrder->authorization, true);
            if (!is_array($authorizationIds)) $authorizationIds = [];
            $userList = User::whereIn('id', $authorizationIds)->get()->toArray();
            $usernames = array_column($userList, 'username', 'id');
            $authorizationStr = implode(', ', array_map(function ($id) use ($usernames) {
                return $usernames[$id] ?? 'Unknown';
            }, $authorizationIds));

            $supplier = Supplier::find($preOrder->supplier_id);

            $details = DB::table('pre_purchase_order_details')
                ->leftJoin('units', 'units.id', '=', 'pre_purchase_order_details.unit_id')
                ->select('pre_purchase_order_details.*', 'units.name as unit_name')
                ->where('pre_purchase_order_id', $id)
                ->get()
                ->toArray();

            $items = Item::all()->toArray();
            $itemMap = array_column($items, 'name', 'id');

            return view('pre_purchase_orders.show', [
                'pre_order' => $preOrder,
                'authorization_str' => $authorizationStr,
                'supplier' => $supplier,
                'details' => $details,
                'item_map' => $itemMap,
                'can_write' => $this->canWrite(),
                'title' => 'Detail Pre Purchase Order'
            ]);
        }
    }

    // Mark as completed and convert to Purchase
    public function markCompleted($id)
    {
        $this->requireWriteAccess();

        $preOrder = DB::table('pre_purchase_orders')->where('id', $id)->first();
        if (!$preOrder) {
            return redirect()->route('pre-purchase-orders.index')
                ->with('error', 'Pre Purchase Order tidak ditemukan');
        }

        if ($preOrder->status == 'completed') {
            return redirect()->route('pre-purchase-orders.show', $id)
                ->with('error', 'Pre Purchase Order sudah ditandai selesai sebelumnya.');
        }

        $existingPurchase = DB::table('purchases')->where('pre_purchase_order_id', $preOrder->id)->first();

        if ($existingPurchase) {
            DB::table('pre_purchase_orders')->where('id', $id)->update(['status' => 'completed']);
            return redirect()->route('pre-purchase-orders.show', $id)
                ->with('error', 'Pre Purchase Order ini sudah pernah masuk ke purchase. Status diubah menjadi selesai.');
        }

        $details = DB::table('pre_purchase_order_details')->where('pre_purchase_order_id', $id)->get();
        if ($details->isEmpty()) {
            return redirect()->route('pre-purchase-orders.show', $id)
                ->with('error', 'Pre Purchase Order tidak memiliki detail barang.');
        }

        DB::beginTransaction();

        try {
            // Update status to completed
            $updateResult = DB::table('pre_purchase_orders')->where('id', $id)->update(['status' => 'completed']);
            if (!$updateResult) {
                throw new \Exception('Gagal mengubah status Pre Purchase Order');
            }

            // Generate purchase number using PurchaseController method
            $purchaseNumber = $this->generatePurchaseNumber($preOrder->issue_date);

            $purchaseData = [
                'purchase_number' => $purchaseNumber,
                'pre_purchase_order_id' => $preOrder->id,
                'issue_date' => $preOrder->issue_date,
                'supplier_id' => $preOrder->supplier_id,
                'description' => $preOrder->description . ' (dari Pre Purchase Order: ' . $preOrder->order_number . ')',
                'authorization' => $preOrder->authorization,
                'total_amount' => $preOrder->total_amount,
                'status' => 'completed',
                'payment_method' => $preOrder->payment_method
            ];

            $purchaseId = DB::table('purchases')->insertGetId($purchaseData);
            if (!$purchaseId) {
                throw new \Exception('Gagal insert data purchase');
            }

            foreach ($details as $detail) {
                $itemId = $detail->item_id;
                $unitId = $detail->unit_id;

                $conversionRow = DB::table('unit_conversions')
                    ->where('item_id', $itemId)
                    ->where('unit_id', $unitId)
                    ->first();
                $conversion = $conversionRow ? (int)$conversionRow->conversion_rate : 1;

                $quantity = (int)$detail->quantity;
                $baseQuantity = $quantity * $conversion;

                $detailInsert = DB::table('purchase_details')->insert([
                    'purchase_id' => $purchaseId,
                    'item_id' => $itemId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'purchase_price' => $detail->price,
                    'subtotal' => $detail->subtotal
                ]);

                if (!$detailInsert) {
                    throw new \Exception('Gagal insert detail purchase untuk item ID: ' . $itemId);
                }

                $updateStock = DB::table('items')
                    ->where('id', $itemId)
                    ->increment('stock', $baseQuantity);

                if (!$updateStock) {
                    throw new \Exception('Gagal update stok item ID: ' . $itemId);
                }
            }

            // Log activities
            DB::table('activity_logs')->insert([
                'user_id' => session('user_id'),
                'activity' => 'Barang dari Pre Purchase Order ' . $preOrder->order_number . ' masuk ke Purchase ' . $purchaseNumber,
                'created_at' => now()
            ]);

            DB::table('activity_logs')->insert([
                'user_id' => session('user_id'),
                'activity' => 'Menandai Pre Purchase Order ' . $preOrder->order_number . ' selesai dan otomatis masuk ke purchase',
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('pre-purchase-orders.show', $id)
                ->with('success', 'Pre Purchase Order berhasil ditandai selesai dan barang masuk ke purchase dengan nomor: ' . $purchaseNumber);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in markCompleted: ' . $e->getMessage());
            return redirect()->route('pre-purchase-orders.show', $id)
                ->with('error', 'Gagal memproses Pre Purchase Order: ' . $e->getMessage());
        }
    }

    // Generate purchase number
    private function generatePurchaseNumber($issueDate)
    {
        $year = date('Y', strtotime($issueDate));
        $month = date('m', strtotime($issueDate));

        $count = DB::table('purchases')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->count();

        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return "PUR/$year/$sequence/$month";
    }

    // Helper untuk cek apakah data berubah
    private function isDataChanged($preOrder, $request, $newDetails)
    {
        $fieldsToCheck = [
            'order_number',
            'issue_date',
            'supplier_id',
            'description',
            'total_amount',
            'tax',
            'due_date',
            'status',
            'payment_method'
        ];

        foreach ($fieldsToCheck as $field) {
            if ($preOrder->$field != $request->input($field)) {
                return true;
            }
        }

        // Check if details changed
        $oldDetails = DB::table('pre_purchase_order_details')->where('pre_purchase_order_id', $preOrder->id)->get();

        if (count($oldDetails) != count($newDetails)) {
            return true;
        }

        // Normalize and compare details
        $normalizeDetail = function ($detail) {
            return [
                'item_id' => (int)($detail['item_id'] ?? $detail->item_id ?? 0),
                'unit_id' => (int)($detail['unit_id'] ?? $detail->unit_id ?? 0),
                'quantity' => (float)($detail['quantity'] ?? $detail->quantity ?? 0),
                'price' => (float)($detail['price'] ?? $detail->price ?? 0),
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

    // Print PDF
    public function print($id)
    {
        $this->requireReadAccess();

        $preOrder = DB::table('pre_purchase_orders')->where('id', $id)->first();
        if (!$preOrder) {
            abort(404, 'Pre Purchase Order tidak ditemukan');
        }

        $details = DB::table('pre_purchase_order_details')
            ->leftJoin('units', 'units.id', '=', 'pre_purchase_order_details.unit_id')
            ->select('pre_purchase_order_details.*', 'units.name as unit_name')
            ->where('pre_purchase_order_id', $id)
            ->get();

        $supplier = Supplier::find($preOrder->supplier_id);
        $items = Item::all()->toArray();
        $itemMap = array_column($items, 'name', 'id');

        // Get creator name from first authorization user
        $authorizationIds = [];
        if (!empty($preOrder->authorization)) {
            $authorizationIds = json_decode($preOrder->authorization, true);
            if (!is_array($authorizationIds)) $authorizationIds = [];
        }

        $creatorName = '';
        if (!empty($authorizationIds)) {
            $creator = User::find($authorizationIds[0]);
            if ($creator && !empty($creator->username)) {
                $creatorName = $creator->username;
            }
        }

        $logoPath = public_path('img/image.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $html = view('pre_purchase_orders.print', [
            'pre_order' => $preOrder,
            'details' => $details,
            'supplier' => $supplier,
            'item_map' => $itemMap,
            'creator_name' => $creatorName,
            'logo_base64' => $logoBase64,
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        $filename = str_replace(['/', '\\'], '_', $preOrder->order_number) . '.pdf';
        return $pdf->stream($filename);
    }

    // Export Excel
    public function export(Request $request)
    {
        $this->requireReadAccess();

        $type = $request->get('type', 'monthly');
        $query = DB::table('pre_purchase_orders')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'pre_purchase_orders.supplier_id')
            ->select('pre_purchase_orders.*', 'suppliers.name as supplier_name');

        if ($type === 'daily') {
            $date = $request->get('issue_date') ?? date('Y-m-d');
            $query->whereDate('pre_purchase_orders.issue_date', $date);
            $filename = "Pre_Purchase_Order_Daily_" . $date . ".xlsx";
        } elseif ($type === 'monthly') {
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('pre_purchase_orders.issue_date', $month)
                ->whereYear('pre_purchase_orders.issue_date', $year);
            $filename = "Pre_Purchase_Order_Monthly_{$month}_{$year}.xlsx";
        } elseif ($type === 'yearly') {
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereYear('pre_purchase_orders.issue_date', $year);
            $filename = "Pre_Purchase_Order_Yearly_{$year}.xlsx";
        } else {
            // Default monthly
            $month = (int)($request->get('month') ?? date('n'));
            $year = (int)($request->get('year') ?? date('Y'));
            $query->whereMonth('pre_purchase_orders.issue_date', $month)
                ->whereYear('pre_purchase_orders.issue_date', $year);
            $filename = "Pre_Purchase_Order_Monthly_{$month}_{$year}.xlsx";
        }

        $query->orderBy('pre_purchase_orders.issue_date', 'ASC');
        $preOrders = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor Pre PO');
        $sheet->setCellValue('C1', 'Tanggal Terbit');
        $sheet->setCellValue('D1', 'Supplier');
        $sheet->setCellValue('E1', 'Total Harga');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Metode Pembayaran');
        $sheet->setCellValue('H1', 'Nama Barang');
        $sheet->setCellValue('I1', 'Qty');
        $sheet->setCellValue('J1', 'Satuan');
        $sheet->setCellValue('K1', 'Harga');
        $sheet->setCellValue('L1', 'Subtotal');

        $row = 2;
        foreach ($preOrders as $i => $preOrder) {
            // Get item details
            $details = DB::table('pre_purchase_order_details')
                ->leftJoin('items', 'items.id', '=', 'pre_purchase_order_details.item_id')
                ->leftJoin('units', 'units.id', '=', 'pre_purchase_order_details.unit_id')
                ->select('pre_purchase_order_details.*', 'items.name as item_name', 'units.name as unit_name')
                ->where('pre_purchase_order_id', $preOrder->id)
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
            $sheet->setCellValue('B' . $startRow, $preOrder->order_number);
            $sheet->setCellValue('C' . $startRow, $preOrder->issue_date);
            $sheet->setCellValue('D' . $startRow, $preOrder->supplier_name);
            $sheet->setCellValue('E' . $startRow, $preOrder->total_amount);
            $sheet->setCellValue('F' . $startRow, $preOrder->status);
            $sheet->setCellValue('G' . $startRow, $preOrder->payment_method);

            // Fill item details in columns H-L
            if ($detailCount > 0) {
                foreach ($details as $detail) {
                    $sheet->setCellValue('H' . $row, $detail->item_name);
                    $sheet->setCellValue('I' . $row, $detail->quantity);
                    $sheet->setCellValue('J' . $row, $detail->unit_name);
                    $sheet->setCellValue('K' . $row, $detail->price);
                    $sheet->setCellValue('L' . $row, $detail->subtotal);
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