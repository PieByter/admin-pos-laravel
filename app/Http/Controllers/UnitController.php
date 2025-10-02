<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    // Tampilkan daftar unit
    public function index()
    {
        return view('units.index');
    }

    // Tampilkan form tambah unit
    public function create()
    {
        // return view('units.create');
    }

    // Simpan unit baru
    public function store(Request $request)
    {
        // proses simpan unit
    }

    // Tampilkan detail unit
    public function show($id)
    {
        // return view('units.show', compact('id'));
    }

    // Tampilkan form edit unit
    public function edit($id)
    {
        // return view('units.edit', compact('id'));
    }

    // Update unit
    public function update(Request $request, $id)
    {
        // proses update unit
    }

    // Hapus unit
    public function destroy($id)
    {
        // proses hapus unit
    }
}
