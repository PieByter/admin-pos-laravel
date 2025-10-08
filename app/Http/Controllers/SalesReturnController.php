<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
{
    public function index()
    {
        $salesReturns = SalesReturn::with(['salesOrder', 'customer', 'creator'])
            ->orderBy('return_date', 'desc')
            ->get();

        return view('sales_returns.index', compact('salesReturns'));
    }

    public function show($id)
    {
        $salesReturn = SalesReturn::with(['salesOrder', 'customer', 'items.item', 'creator', 'approver'])
            ->findOrFail($id);

        return view('sales_returns.show', compact('salesReturn'));
    }

    public function create()
    {
        $salesOrders = SalesOrder::with('customer')->get();

        return view('sales_returns.create', compact('salesOrders'));
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data sales return
    }

    public function edit($id)
    {
        $salesReturn = SalesReturn::with(['salesOrder', 'items'])->findOrFail($id);

        return view('sales_returns.edit', compact('salesReturn'));
    }

    public function update(Request $request, $id)
    {
        // Validasi dan update data sales return
    }

    public function destroy($id)
    {
        $salesReturn = SalesReturn::findOrFail($id);
        $salesReturn->delete();

        return redirect()->route('sales-returns.index')->with('success', 'Data retur penjualan berhasil dihapus.');
    }
}