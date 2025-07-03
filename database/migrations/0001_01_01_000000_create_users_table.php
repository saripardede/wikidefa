<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // Unique username
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique()->nullable(); // Unique number phone
            $table->enum('role', ['user', 'admin'])->default('user'); // Lengkap
            $table->string('posisi');
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('last_login')->nullable();
        });

        // Mengecek apakah kolom 'last_login' sudah ada
    if (!Schema::hasColumn('users', 'last_login')) {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login')->nullable();
        });
    }
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
