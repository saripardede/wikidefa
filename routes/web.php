<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\TutorialController;

// =========================
// ROOT: Login / Dashboard
// =========================
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : view('auth.login');
})->name('index');

// =========================
// AUTH ROUTES
// =========================
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// =========================
// AUTHENTICATED ROUTES
// =========================
Route::middleware('auth')->group(function () {

    // Redirect dashboard berdasarkan role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.index');
    });

    // ========= ADMIN ROUTES =========
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/user-management', [AdminController::class, 'userManagement'])->name('user-management');
        Route::get('/users', [AdminController::class, 'userManagement'])->name('users.index');
        

        // Detail tutorial untuk admin
        Route::get('/tutorial/{id}', [TutorialController::class, 'show'])->name('tutorial.detail');
        Route::get('/tutorial-approval', [TutorialController::class, 'approvalPage'])->name('tutorial-approval');
        Route::put('/tutorial/{id}/status', [TutorialController::class, 'updateStatus'])->name('tutorial.updateStatus');
        Route::post('/tutorial/{id}/approval', [TutorialController::class, 'approve'])->name('tutorial.approval');
        Route::post('/tutorial/{id}/pending', [TutorialController::class, 'pending'])->name('admin.tutorial.pending');
        Route::post('/tutorial/{id}/reject', [TutorialController::class, 'reject'])->name('tutorial.reject');


        // Approval user
        Route::post('/user/{id}/approve', [AdminController::class, 'approveUser'])->name('user.approve');
        Route::post('/user/{id}/reject', [AdminController::class, 'rejectUser'])->name('user.reject');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Reward
        Route::get('/reward', [RewardController::class, 'index'])->name('reward.index');
        Route::get('/reward/create', [RewardController::class, 'create'])->name('reward.create');
        Route::post('/reward', [RewardController::class, 'store'])->name('reward.store');
    });

    // ========= USER ROUTES =========
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('index');
        Route::get('/information', [UserController::class, 'information'])->name('information');
        Route::get('/contribution', [UserController::class, 'contribution'])->name('contribution');
        Route::get('/reward', [RewardController::class, 'reward'])->name('reward');
        Route::get('/leaderboard', [RewardController::class, 'userleaderboard'])->name('leaderboard');
        Route::get('/dashboard', [RewardController::class, 'somePageWithSidebar'])->name('dashboard');

        

        // User melihat detail tutorial
        Route::get('/tutorial/{id}', [TutorialController::class, 'show'])->name('tutorial.detail');
        Route::get('/tutorials', [TutorialController::class, 'index'])->name('tutorials.index');

    });

    Route::get('/informasi', [UserController::class, 'information'])->name('user.information');
    Route::get('/kategori/{id}', [UserController::class, 'showKategori'])->name('user.kategori.show');
    Route::get('/search-tutorial', [UserController::class, 'searchTutorial'])->name('search.tutorial');
    Route::get('/kategori/{kategori}/search', [UserController::class, 'searchByKategori'])->name('kategori.search');

    // ========= TUTORIAL ROUTES (UMUM) =========
    Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial.index');
    Route::post('/tutorial', [TutorialController::class, 'store'])->name('tutorial.store');
    //Route::get('/tutorial/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/kategori/{kategori}', [TutorialController::class, 'byKategori'])->name('kategori-postingan');

    // Route untuk menampilkan form edit tutorial
    Route::get('/tutorial/{id}/edit', [TutorialController::class, 'edit'])->name('tutorial.edit');

    // Route untuk menyimpan revisi tutorial
    Route::post('/tutorial/{id}', [TutorialController::class, 'update'])->name('tutorial.update');
    Route::put('/tutorial/{id}', [TutorialController::class, 'update'])->name('tutorial.update');
    



});
