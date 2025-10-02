<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index()
    {
        return view('item_categories.index');
    }

    public function create()
    {
        // return view('item_categories.create');
    }

    public function store(Request $request)
    {
        // proses simpan item category
    }

    public function show($id)
    {
        // return view('item_category.show', compact('id'));
    }

    public function edit($id)
    {
        // return view('item_category.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // proses update item category
    }

    public function destroy($id)
    {
        // proses hapus item category
    }
}
