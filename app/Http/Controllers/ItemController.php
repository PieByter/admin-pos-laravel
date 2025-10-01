<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // return view('items.index');
    }

    public function create()
    {
        // return view('items.create');
    }

    public function store(Request $request)
    {
        // proses simpan item
    }

    public function show($id)
    {
        // return view('items.show', compact('id'));
    }

    public function edit($id)
    {
        // return view('items.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // proses update item
    }

    public function destroy($id)
    {
        // proses hapus item
    }
}