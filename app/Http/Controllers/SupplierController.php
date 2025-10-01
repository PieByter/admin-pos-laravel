<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // Tampilkan daftar supplier
    public function index()
    {
        // return view('suppliers.index');
    }

    // Tampilkan form tambah supplier
    public function create()
    {
        // return view('suppliers.create');
    }

    // Simpan supplier baru
    public function store(Request $request)
    {
        // proses simpan supplier
    }

    // Tampilkan detail supplier
    public function show($id)
    {
        // return view('suppliers.show', compact('id'));
    }

    // Tampilkan form edit supplier
    public function edit($id)
    {
        // return view('suppliers.edit', compact('id'));
    }

    // Update supplier
    public function update(Request $request, $id)
    {
        // proses update supplier
    }

    // Hapus supplier
    public function destroy($id)
    {
        // proses hapus supplier
    }
}