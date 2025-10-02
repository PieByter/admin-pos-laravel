<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    // Tampilkan daftar log
    public function index()
    {
        return view('logs.index');
    }

    // Tampilkan form tambah log (jika ada)
    public function create()
    {
        // return view('logs.create');
    }

    // Simpan log baru (jika ada)
    public function store(Request $request)
    {
        // proses simpan log
    }

    // Tampilkan detail log
    public function show($id)
    {
        // return view('logs.show', compact('id'));
    }

    // Tampilkan form edit log (jika ada)
    public function edit($id)
    {
        // return view('logs.edit', compact('id'));
    }

    // Update log (jika ada)
    public function update(Request $request, $id)
    {
        // proses update log
    }

    // Hapus log
    public function destroy($id)
    {
        // proses hapus log
    }
}