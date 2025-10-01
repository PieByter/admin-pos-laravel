<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }
    public function update(Request $request)
    {

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }
}