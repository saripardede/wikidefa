<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->get('per_page', 10); // Default 10 per halaman

    // Ambil semua user yang punya tutorial approved, beserta jumlah poinnya
    $allUsers = User::withCount(['tutorials as tutorials_count' => function ($query) {
            $query->where('status', 'approved');
        }])
        ->with('rewards') // untuk ambil poin reward
        ->get()
        ->filter(function ($user) {
            return $user->tutorials_count > 0;
        });

    // Hitung skor & level, dan urutkan berdasarkan skor tertinggi
    $allUsers = $allUsers->map(function ($user) {
        $user->skor = $user->rewards->sum('poin');
        $user->level = $this->calculateLevel($user->skor);
        return $user;
    })->sortByDesc('skor')->values();

    // Tambah rank ke setiap user
    $allUsers->each(function ($user, $index) {
        $user->rank = $index + 1;
    });

    // Pagination manual (karena kita sudah pakai Collection, bukan Query Builder)
    $currentPage = $request->get('page', 1);
    $pagedUsers = $allUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
        $pagedUsers,
        $allUsers->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('admin.rewards.index', ['users' => $paginatedUsers]);
}


    private function calculateLevel($points)
    {
        if ($points >= 2000) return 'Level 7';
        if ($points >= 1500) return 'Level 6';
        if ($points >= 1000) return 'Level 5';
        if ($points >= 500) return 'Level 4';
        if ($points >= 300) return 'Level 3';
        if ($points >= 50) return 'Level 2';
        if ($points >= 1) return 'Level 1';
        return 'Level 0';
    }

    public function userLeaderboard(Request $request)
{
    $perPage = $request->get('per_page', 10);

    $users = User::where('role', 'user') // ❗ filter hanya user biasa
        ->withCount(['tutorials as tutorials_count' => function ($q) {
            $q->where('status', 'approved');
        }])
        ->with('rewards')
        ->get()
        ->sortByDesc(function ($user) {
            return $user->rewards->sum('poin');
        })
        ->values();

    foreach ($users as $index => $user) {
        $user->rank = $index + 1;
        $user->skor = $user->rewards->sum('poin');
        $user->level = $this->calculateLevel($user->skor);
    }

    // hitung total semua user untuk pagination yang benar
    $totalUsers = $users->count();

    $users = $users->forPage($request->get('page', 1), $perPage);
    $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
        $users,
        $totalUsers, // ❗ ini harus jumlah total semua user (sebelum paginasi)
        $perPage,
        $request->get('page', 1),
        ['path' => url()->current()]
    );

    return view('user.leaderboard', ['users' => $paginatedUsers]);
}


// di RewardController.php
public function somePageWithSidebar()
{
    $users = User::with(['rewards'])
        ->withCount(['tutorials as tutorials_count' => function ($q) {
            $q->where('status', 'approved');
        }])
        ->get()
        ->map(function ($user) {
            $user->poin = $user->rewards->sum('poin');
            $user->tanggal = optional($user->rewards->first())->created_at?->format('d-m-Y') ?? '-';
            return $user;
        })
        ->sortByDesc('poin')
        ->values();

    // Tambahkan rank SEBELUM di-take(3)
    foreach ($users as $index => $user) {
        $user->rank = $index + 1;
    }

    // Ambil 3 besar SETELAH ada rank-nya
    $topUsers = $users->take(3);

    // Ambil tutorial untuk ditampilkan
    $tutorials = \App\Models\Tutorial::with('user')->where('status', 'approved')->latest()->get();

    return view('layouts.user', compact('topUsers', 'tutorials'));
}


}
