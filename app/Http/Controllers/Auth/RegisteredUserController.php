<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
{
    // Hapus dd($request->all());

    // Validasi input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'phone' => 'required|numeric|digits_between:8,13|unique:users',
        'role' => 'required|in:admin,user',
        'posisi' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    try {
        // Simpan data user
        User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'role' => $validatedData['role'],
            'posisi' => $validatedData['posisi'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Redirect dengan notifikasi sukses
        return redirect()->route('login')->with('register_success', true);

    } catch (Exception $e) {
        // Error handling jika terjadi kesalahan saat penyimpanan
        return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    }
}

}
