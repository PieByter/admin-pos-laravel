<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index()
    {
        return view('users.index');
    }

    // Tampilkan form tambah user
    public function create()
    {
        // return view('user.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        // proses simpan user
    }

    // Tampilkan detail user
    public function show($id)
    {
        // return view('user.show', compact('id'));
    }

    // Tampilkan form edit user
    public function edit($id)
    {
        // return view('user.edit', compact('id'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        // proses update user
    }

    // Hapus user
    public function destroy($id)
    {
        // proses hapus user
    }
}
