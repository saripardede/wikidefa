<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric|digits_between:8,13|unique:users',
            'role' => 'required|in:admin,user',
            'posisi' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'posisi' => $request->posisi,
            'password' => Hash::make($request->password),
            'status' => 'pending' // agar masuk ke notifikasi admin
        ]);
    
        return redirect()->route('login')->with('register_success', true);
    }    

    // Login User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Username atau password salah!'], 401);
        }

        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    // Logout User
    public function logout(Request $request)
    {
    Auth::logout(); // keluar dari sesi

    $request->session()->invalidate(); // reset sesi
    $request->session()->regenerateToken(); // regenerasi CSRF token

    return redirect('/login'); // atau sesuai halaman login kamu
    }

}
