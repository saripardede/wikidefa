<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorial;
use App\Models\Reward;
use Carbon\Carbon;
use App\Http\Requests\StoreTutorialRequest;
use App\Http\Requests\UpdateTutorialRequest;


class TutorialController extends Controller
{
    // Menampilkan halaman tutorial user
    public function index(Request $request)
    {
        $query = Tutorial::with('user');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $tutorials = $query->latest()->get();
        $user = Auth::user();

        return view('user.tutorial', compact('tutorials', 'user'));
    }


    // Menyimpan tutorial baru ke database
    public function store(StoreTutorialRequest $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors('Anda harus login terlebih dahulu.');
        }

        $isiArray = $request->input('isi', []);
        $mediaArray = $request->file('media', []);

        $allIndexes = array_unique(array_merge(
            array_keys($isiArray),
            array_keys($mediaArray)
        ));

        $items = [];

        foreach ($allIndexes as $index) {
            $deskripsi = $isiArray[$index] ?? '';
            $mediaFiles = $mediaArray[$index] ?? [];

            $item = [
                'deskripsi' => $deskripsi,
                'media' => [],
            ];

            foreach ((array) $mediaFiles as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads', $filename, 'public');
                    $item['media'][] = $path;
                }
            }

            $items[] = $item;
        }

        Tutorial::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => json_encode($items),
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tutorial.index')->with('success', 'Tutorial berhasil dikirim, menunggu persetujuan admin.');
    }

    public function approvalPage(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $sevenDaysAgo = Carbon::now()->subDays(7);

        $query = Tutorial::with('user');

        // Filter status
        if ($status) {
            $query->where('status', $status);

            // Filter tanggal hanya jika status = approved
            if ($status === 'approved') {
                $query->where('created_at', '>=', $sevenDaysAgo);
            }
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                ->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        $tutorials = $query->latest()->get();

        // Hitung jumlah untuk info ringkasan
        $jumlahTutorialDisetujui = Tutorial::where('status', 'approved')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->count();

        $jumlahTutorialBaru = Tutorial::where('status', 'pending')->count();

        return view('admin.tutorial-approval', compact(
            'tutorials',
            'jumlahTutorialDisetujui',
            'jumlahTutorialBaru'
        ));
    }

    // Mengupdate status tutorial (approve/reject/revisi)
    //Update status tutorial
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected,revision,pending',
        'catatan_revisi' => 'nullable|string|max:1000',
    ]);

    $tutorial = Tutorial::findOrFail($id);
    $tutorial->status = $request->status;
    $tutorial->catatan_revisi = $request->catatan_revisi ?? null;
    $tutorial->save();

    $existingReward = Reward::where('tutorial_id', $tutorial->id)->first();

    if ($request->status === 'approved') {
        // Ambil poin dari catatan (misalnya "Diterima 15 poin")
        $poin = 10;
        if ($request->catatan_revisi && preg_match('/(\d+)/', $request->catatan_revisi, $match)) {
            $poin = (int)$match[1];
        }

        if ($existingReward) {
            $existingReward->update([
                'poin' => $poin,
            ]);
        } else {
            Reward::create([
                'user_id' => $tutorial->user_id,
                'tutorial_id' => $tutorial->id,
                'poin' => $poin,
            ]);
        }

    } else {
        // Jika status bukan approved, hapus reward jika ada
        if ($existingReward) {
            $existingReward->delete();
        }
    }

    return redirect()->route('admin.tutorial-approval')->with('success', 'Status tutorial berhasil diperbarui.');
}


    public function approve($postId)
{
    $tutorial = Tutorial::findOrFail($postId);
    $tutorial->status = 'approved';
    $tutorial->save();

    // Tambahkan reward (default 10 poin)
    Reward::updateOrCreate(
        ['tutorial_id' => $tutorial->id],
        [
            'user_id' => $tutorial->user_id,
            'poin' => 10,
        ]
    );

    return redirect()->route('admin.tutorial-approval')->with('success', 'Tutorial disetujui.');
}

public function reject($postId)
{
    $tutorial = Tutorial::findOrFail($postId);
    $tutorial->status = 'rejected';
    $tutorial->save();

    // Hapus reward jika ada
    Reward::where('tutorial_id', $tutorial->id)->delete();

    return redirect()->route('admin.tutorial-approval')->with('success', 'Tutorial ditolak.');
}

public function pending($postId)
{
    $tutorial = Tutorial::findOrFail($postId);
    $tutorial->status = 'pending';
    $tutorial->save();

    // ⛔ Hapus reward jika ada
    Reward::where('tutorial_id', $tutorial->id)->delete();

    return redirect()->route('admin.tutorial-approval')->with('success', 'Tutorial dikembalikan ke status pending.');
}




    // Menampilkan tutorial yang sudah disetujui
    public function listApproved()
    {
        $tutorials = Tutorial::where('status', 'approved')->latest()->get();
        return view('user.index', compact('tutorials'));
    }

    // Menampilkan detail tutorial berdasarkan ID 
    public function show($id)
    {
        $tutorial = Tutorial::with('user')->findOrFail($id);
        return view('admin.tutorial-detail', compact('tutorial'));
    }

    public function showByKategori($kategori, $id)
    {
        $tutorial = Tutorial::with('user')
            ->where('id', $id)
            ->where('kategori', $kategori)
            ->where('status', 'approved')
            ->firstOrFail();

        return view('kategori-postingan', [
            'kategori' => $kategori,
            'tutorials' => collect([$tutorial]) // bungkus dalam collection agar bisa di-loop
        ]);
    }




        // TutorialController.php
    public function edit($id)
    {
        $tutorial = Tutorial::findOrFail($id);

        // Optional: pastikan hanya pemilik tutorial yang bisa edit
        if (auth()->id() !== $tutorial->user_id) {
            abort(403);
        }

        return view('user.edit-tutorial', compact('tutorial'));
    }

    public function update(UpdateTutorialRequest $request, $id)
    {
        $tutorial = Tutorial::findOrFail($id);

        if (auth()->id() !== $tutorial->user_id) {
            abort(403);
        }

        $isiArray = $request->input('isi', []);
        $mediaArray = $request->file('media', []);

        $allIndexes = array_unique(array_merge(
            array_keys($isiArray),
            array_keys($mediaArray)
        ));

        $items = [];

        foreach ($allIndexes as $index) {
            $deskripsi = $isiArray[$index] ?? '';
            $mediaFiles = $mediaArray[$index] ?? [];

            $item = [
                'deskripsi' => $deskripsi,
                'media' => [],
            ];

            foreach ((array) $mediaFiles as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads', $filename, 'public');
                    $item['media'][] = $path;
                }
            }

            $items[] = $item;
        }

        $tutorial->update([
            'judul' => $request->judul,
            'isi' => json_encode($items),
            'status' => 'pending',
            'note' => null,
        ]);

        return redirect()->route('user.contribution')->with('success', 'Tutorial berhasil diperbarui.');
    }
}




//COBA AJA
//public function show($kategori, $judul)
//{
//    $tutorial = Tutorial::where('kategori', $kategori)
//                        ->where('judul', $judul)
//                        ->where('status', 'approved') // Opsional, tergantung kamu ingin tampilkan semua atau tidak
//                        ->firstOrFail();
//
//    return view('tutorials.show', compact('tutorial'));
//}