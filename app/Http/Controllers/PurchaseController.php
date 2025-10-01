<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        // return view('purchases.index');
    }

    public function create()
    {
        // return view('purchases.create');
    }

    public function store(Request $request)
    {
        // proses simpan purchase
    }

    public function show($id)
    {
        // return view('purchases.show', compact('id'));
    }

    public function edit($id)
    {
        // return view('purchases.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // proses update purchase
    }

    public function destroy($id)
    {
        // proses hapus purchase
    }
}