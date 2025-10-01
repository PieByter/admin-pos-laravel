<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemGroupController extends Controller
{
    public function index()
    {
        // return view('item_groups.index');
    }

    public function create()
    {
        // return view('item_groups.create');
    }

    public function store(Request $request)
    {
        // proses simpan item group
    }

    public function show($id)
    {
        // return view('item_groups.show', compact('id'));
    }

    public function edit($id)
    {
        // return view('item_groups.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // proses update item group
    }

    public function destroy($id)
    {
        // proses hapus item group
    }
}