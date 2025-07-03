<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => '1',
            'name' => 'Yos',
            'username' => 'yos123',
            'email' => 'adminganteng@gmail.com',
            'password' => Hash::make('password123'), // Ganti dengan password yang diinginkan
            'phone' => '08123456789',
            'role' => 'admin',
            'posisi' => 'Super Admin',
            'status' => 'active',
            'created_at' => now(),
        ]);
    }
}
