<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Tampilkan daftar customer
    public function index()
    {
        return view('customers.index');
    }

    // Tampilkan form tambah customer
    public function create()
    {
        // return view('customers.create');
    }

    // Simpan customer baru
    public function store(Request $request)
    {
        // proses simpan customer
    }

    // Tampilkan detail customer
    public function show($id)
    {
        // return view('customers.show', compact('id'));
    }

    // Tampilkan form edit customer
    public function edit($id)
    {
        // return view('customers.edit', compact('id'));
    }

    // Update customer
    public function update(Request $request, $id)
    {
        // proses update customer
    }

    // Hapus customer
    public function destroy($id)
    {
        // proses hapus customer
    }
}