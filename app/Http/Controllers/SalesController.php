<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales_orders.index');
    }

    public function create()
    {
        // return view('sales.create');
    }

    public function store(Request $request)
    {
        // proses simpan sales
    }

    public function show($id)
    {
        // return view('sales.show', compact('id'));
    }

    public function edit($id)
    {
        // return view('sales.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // proses update sales
    }

    public function destroy($id)
    {
        // proses hapus sales
    }
}