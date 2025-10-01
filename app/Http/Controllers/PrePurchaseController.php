<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrePurchaseController extends Controller
{
    public function index()
    {
        // return view('pre_purchases.index');
    }

    public function create()
    {
        // return view('pre_purchases.create');
    }

    public function store(Request $request)
    {
        // proses simpan pre purchase
    }

    public function show($id)
    {
        // return view('pre_purchases.show', compact('id'));
    }

    public function edit($id)
    {
        // return view('pre_purchases.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // proses update pre purchase
    }

    public function destroy($id)
    {
        // proses hapus pre purchase
    }
}