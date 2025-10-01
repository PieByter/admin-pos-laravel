<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitConversionController extends Controller
{
    // Tampilkan daftar unit conversion
    public function index()
    {
        return view('unit_conversions.index');
    }

    // Tampilkan form tambah unit conversion
    public function create()
    {
        // return view('unit_conversions.create');
    }

    // Simpan unit conversion baru
    public function store(Request $request)
    {
        // proses simpan unit conversion
    }

    // Tampilkan detail unit conversion
    public function show($id)
    {
        // return view('unit_conversions.show', compact('id'));
    }

    // Tampilkan form edit unit conversion
    public function edit($id)
    {
        // return view('unit_conversions.edit', compact('id'));
    }

    // Update unit conversion
    public function update(Request $request, $id)
    {
        // proses update unit conversion
    }

    // Hapus unit conversion
    public function destroy($id)
    {
        // proses hapus unit conversion
    }
}