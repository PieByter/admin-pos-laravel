<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;
use App\Models\Purchase;
use App\Models\Sale;

class TransactionController extends Controller
{
    public function index()
    {
        // Ringkasan pendapatan & pengeluaran
        $totalRevenue = $this->getTotalRevenue();
        $totalExpense = $this->getTotalExpense();
        $netIncome = $totalRevenue - $totalExpense;

        // Ringkasan hutang & piutang
        $totalDebtUnpaid = $this->getTotalDebtUnpaid();
        $totalDebtPaid = $this->getTotalDebtPaid();
        $totalReceivableUnpaid = $this->getTotalReceivableUnpaid();
        $totalReceivablePaid = $this->getTotalReceivablePaid();

        // Detail hutang (Purchase Orders yang belum lunas)
        $debtList = DB::table('purchase_orders')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
            ->select('purchase_orders.*', 'suppliers.name as supplier_name')
            ->whereIn('purchase_orders.status', ['process', 'debt'])
            ->orderBy('purchase_orders.due_date', 'ASC')
            ->get();

        // Detail piutang (Sales yang belum lunas)
        $receivableList = DB::table('sales_orders')
            ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
            ->select('sales_orders.*', 'customers.name as customer_name')
            ->whereIn('sales_orders.status', ['debt', 'process'])
            ->orderBy('sales_orders.issue_date', 'DESC')
            ->get();

        // Data transaksi terbaru (untuk dashboard)
        $recentPurchases = DB::table('purchase_orders')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
            ->select('purchase_orders.*', 'suppliers.name as supplier_name')
            ->orderBy('purchase_orders.issue_date', 'DESC')
            ->limit(5)
            ->get();

        $recentSales = DB::table('sales_orders')
            ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
            ->select('sales_orders.*', 'customers.name as customer_name')
            ->orderBy('sales_orders.issue_date', 'DESC')
            ->limit(5)
            ->get();

        // Ringkasan bulanan
        $monthlyData = $this->getMonthlyTransactionSummary();

        $data = [
            'title' => 'Laporan Transaksi',
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_income' => $netIncome,
            'total_debt_unpaid' => $totalDebtUnpaid,
            'total_debt_paid' => $totalDebtPaid,
            'total_receivable_unpaid' => $totalReceivableUnpaid,
            'total_receivable_paid' => $totalReceivablePaid,
            'debt_list' => $debtList,
            'receivable_list' => $receivableList,
            'recent_purchases' => $recentPurchases,
            'recent_sales' => $recentSales,
            'monthly_data' => $monthlyData,
        ];

        return view('transactions.index', $data);
    }

    /**
     * Get total revenue from sales
     */
    private function getTotalRevenue(): float
    {
        return (float) DB::table('sales_orders')
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get total expense from purchases
     */
    private function getTotalExpense(): float
    {
        return (float) DB::table('purchase_orders')
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get total unpaid debt from purchase orders
     */
    private function getTotalDebtUnpaid(): float
    {
        return (float) DB::table('purchase_orders')
            ->whereIn('status', ['process', 'debt'])
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get total paid debt from purchase orders
     */
    private function getTotalDebtPaid(): float
    {
        return (float) DB::table('purchase_orders')
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get total unpaid receivables from sales
     */
    private function getTotalReceivableUnpaid(): float
    {
        return (float) DB::table('sales_orders')
            ->whereIn('status', ['debt', 'process'])
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get total paid receivables from sales
     */
    private function getTotalReceivablePaid(): float
    {
        return (float) DB::table('sales_orders')
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get monthly transaction summary for chart
     */
    private function getMonthlyTransactionSummary(): array
    {
        $currentYear = date('Y');
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthRevenue = (float) DB::table('sales_orders')
                ->whereYear('issue_date', $currentYear)
                ->whereMonth('issue_date', $i)
                ->where('status', 'paid')
                ->sum('total_amount') ?? 0;

            $monthExpense = (float) DB::table('purchase_orders')
                ->whereYear('issue_date', $currentYear)
                ->whereMonth('issue_date', $i)
                ->where('status', 'paid')
                ->sum('total_amount') ?? 0;

            $months[] = [
                'month' => $i,
                'month_name' => date('F', mktime(0, 0, 0, $i, 1)),
                'revenue' => $monthRevenue,
                'expense' => $monthExpense,
                'profit' => $monthRevenue - $monthExpense
            ];
        }

        return $months;
    }

    /**
     * Get transaction summary by date range
     */
    public function getSummaryByDateRange(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));

        $revenue = (float) DB::table('sales_orders')
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;

        $expense = (float) DB::table('purchase_orders')
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;

        return response()->json([
            'revenue' => $revenue,
            'expense' => $expense,
            'profit' => $revenue - $expense,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    /**
     * Get daily transaction data for chart
     */
    public function getDailyTransactionData(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $dailyData = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $dayRevenue = (float) DB::table('sales')
                ->whereDate('issue_date', $date)
                ->where('status', 'paid')
                ->sum('total_amount') ?? 0;

            $dayExpense = (float) DB::table('purchases')
                ->whereDate('issue_date', $date)
                ->where('status', 'paid')
                ->sum('total_amount') ?? 0;

            $dailyData[] = [
                'date' => $date,
                'day' => $day,
                'revenue' => $dayRevenue,
                'expense' => $dayExpense,
                'profit' => $dayRevenue - $dayExpense
            ];
        }

        return response()->json($dailyData);
    }

    /**
     * Get top selling items
     */
    public function getTopSellingItems(Request $request)
    {
        $limit = $request->get('limit', 10);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = DB::table('sale_details')
            ->leftJoin('items', 'items.id', '=', 'sale_details.item_id')
            ->leftJoin('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->select(
                'items.name as item_name',
                DB::raw('SUM(sale_details.quantity) as total_quantity'),
                DB::raw('SUM(sale_details.subtotal) as total_amount')
            )
            ->where('sales.status', 'paid')
            ->groupBy('sale_details.item_id', 'items.name')
            ->orderBy('total_quantity', 'DESC');

        if ($startDate && $endDate) {
            $query->whereBetween('sales.issue_date', [$startDate, $endDate]);
        }

        $topItems = $query->limit($limit)->get();

        return response()->json($topItems);
    }

    /**
     * Get customer transaction summary
     */
    public function getCustomerTransactionSummary(Request $request)
    {
        $limit = $request->get('limit', 10);

        $customers = DB::table('sales')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->select(
                'customers.name as customer_name',
                DB::raw('COUNT(sales.id) as transaction_count'),
                DB::raw('SUM(sales.total_amount) as total_amount'),
                DB::raw('SUM(CASE WHEN sales.status = "paid" THEN sales.total_amount ELSE 0 END) as paid_amount'),
                DB::raw('SUM(CASE WHEN sales.status IN ("debt", "process") THEN sales.total_amount ELSE 0 END) as unpaid_amount')
            )
            ->groupBy('sales.customer_id', 'customers.name')
            ->orderBy('total_amount', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json($customers);
    }

    /**
     * Get supplier transaction summary
     */
    public function getSupplierTransactionSummary(Request $request)
    {
        $limit = $request->get('limit', 10);

        $suppliers = DB::table('purchases')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->select(
                'suppliers.name as supplier_name',
                DB::raw('COUNT(purchases.id) as transaction_count'),
                DB::raw('SUM(purchases.total_amount) as total_amount'),
                DB::raw('SUM(CASE WHEN purchases.status = "paid" THEN purchases.total_amount ELSE 0 END) as paid_amount'),
                DB::raw('SUM(CASE WHEN purchases.status IN ("debt", "process") THEN purchases.total_amount ELSE 0 END) as unpaid_amount')
            )
            ->groupBy('purchases.supplier_id', 'suppliers.name')
            ->orderBy('total_amount', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json($suppliers);
    }

    /**
     * Export transaction summary to Excel
     */
    public function exportSummary(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));

        // Get data
        $revenue = $this->getTotalRevenue();
        $expense = $this->getTotalExpense();

        $salesData = DB::table('sales')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->select('sales.*', 'customers.name as customer_name')
            ->whereBetween('sales.issue_date', [$startDate, $endDate])
            ->orderBy('sales.issue_date', 'DESC')
            ->get();

        $purchasesData = DB::table('purchases')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->select('purchases.*', 'suppliers.name as supplier_name')
            ->whereBetween('purchases.issue_date', [$startDate, $endDate])
            ->orderBy('purchases.issue_date', 'DESC')
            ->get();

        // Create CSV content
        $csv = "RINGKASAN TRANSAKSI\n";
        $csv .= "Periode: {$startDate} s/d {$endDate}\n\n";
        $csv .= "Total Pendapatan: " . number_format($revenue, 0, ',', '.') . "\n";
        $csv .= "Total Pengeluaran: " . number_format($expense, 0, ',', '.') . "\n";
        $csv .= "Pendapatan Bersih: " . number_format($revenue - $expense, 0, ',', '.') . "\n\n";

        $csv .= "DATA PENJUALAN\n";
        $csv .= "No Invoice,Tanggal,Customer,Total,Status\n";
        foreach ($salesData as $sale) {
            $csv .= '"' . $sale->invoice_number . '",';
            $csv .= '"' . $sale->issue_date . '",';
            $csv .= '"' . $sale->customer_name . '",';
            $csv .= '"' . number_format($sale->total_amount, 0, ',', '.') . '",';
            $csv .= '"' . $sale->status . '"' . "\n";
        }

        $csv .= "\nDATA PEMBELIAN\n";
        $csv .= "No Invoice,Tanggal,Supplier,Total,Status\n";
        foreach ($purchasesData as $purchase) {
            $csv .= '"' . $purchase->invoice_number . '",';
            $csv .= '"' . $purchase->issue_date . '",';
            $csv .= '"' . $purchase->supplier_name . '",';
            $csv .= '"' . number_format($purchase->total_amount, 0, ',', '.') . '",';
            $csv .= '"' . $purchase->status . '"' . "\n";
        }

        $filename = 'transaction_summary_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}