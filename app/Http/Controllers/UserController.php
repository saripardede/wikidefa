<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorial;
use App\Models\Kategori;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
{
    $tutorials = Tutorial::where('status', 'approved')->with('media')->get();

    // Ambil user yang hanya memiliki role 'user'
    $users = User::where('role', 'user') // ← ❗ hanya user biasa
        ->with('rewards')
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
        ->values(); // reset index biar bisa pakai rank

    // Tambahkan rank ke tiap user
    foreach ($users as $index => $user) {
        $user->rank = $index + 1;
    }

    // Ambil 3 user teratas
    $topUsers = $users->take(3);

    return view('user.index', compact('tutorials', 'topUsers'));
}


        public function information()
    {
        // Ambil semua kategori unik dari tabel tutorials
        $existingCategories = Tutorial::select('kategori')
            ->whereNotNull('kategori')
            ->where('status', 'approved')
            ->distinct()
            ->pluck('kategori') // hanya ambil array kategori
            ->toArray();

        // Urutan yang diinginkan
        $desiredOrder = ['Baterai', 'Genset', 'AC'];

        // Filter dan urutkan sesuai desiredOrder
        $sortedCategories = array_filter($desiredOrder, function ($kategori) use ($existingCategories) {
            return in_array($kategori, $existingCategories);
        });

        return view('user.information', ['categories' => $sortedCategories]);
    }

    public function showKategori($id)
    {
        $tutorials = Tutorial::where('kategori', $id)
                            ->where('status', 'approved')
                            ->with('media', 'user')
                            ->get();
        return view('user.kategori-postingan', [
            'kategori' => $id,
            'tutorials' => $tutorials
        ]);
    }

        public function contribution()
    {
        $userId = Auth::id(); // Ambil ID user yang sedang login
        $tutorials = Tutorial::where('user_id', $userId)->get(); // Ambil tutorial milik user itu
        return view('user.contribution', compact('tutorials'));
    }

    public function show($id)
    {
        $tutorial = Tutorial::with(['media', 'user'])->findOrFail($id);

        return view('user.show', compact('tutorial'));
    }

    public function searchTutorial(Request $request)
{
    $query = Tutorial::with(['user', 'media'])->where('status', 'approved');

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
            ->orWhere('kategori', 'like', "%{$search}%")
            ->orWhereHas('user', function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%");
            });
        });
    }

    $tutorials = $query->latest()->get();

    // Ambil top 3 user sama seperti di index()
    $users = User::where('role', 'user')
        ->with('rewards')
        ->withCount(['tutorials as tutorials_count' => function ($q) {
            $q->where('status', 'approved');
        }])
        ->get()
        ->map(function ($user) {
            $user->poin = $user->rewards->sum('poin');
            return $user;
        })
        ->sortByDesc('poin')
        ->values();

    $topUsers = $users->take(3);

    return view('user.index', compact('tutorials', 'topUsers'));
}


    public function searchByKategori(Request $request, $kategori)
    {
        $query = Tutorial::with(['user', 'media'])
            ->where('status', 'approved')
            ->where('kategori', $kategori);

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function ($q2) use ($search) {
                $q2->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tutorials = $query->latest()->get();

        return view('user.kategori-postingan', [
            'kategori' => $kategori,
            'tutorials' => $tutorials
        ]);
    }
}
