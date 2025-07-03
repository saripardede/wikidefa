<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tutorial;
use App\Models\Reward;
use Carbon\Carbon;


class AdminController extends Controller
{
    // ========== DASHBOARD ==========
    
public function index()
{
    $sevenDaysAgo = Carbon::now()->subDays(7);

    // Ambil user yang status-nya masih pending (belum disetujui)
    $pendingUsers = User::where('status', 'pending')->get();

    // Ambil tutorial yang status-nya pending
    $pendingTutorials = Tutorial::where('status', 'pending')
                             ->where('created_at', '>=', $sevenDaysAgo)
                             ->get();

    return view('admin.dashboard', [
        'pendingUsers' => $pendingUsers,
        'pendingTutorials' => $pendingTutorials,
        'jumlahPenggunaBaru' => User::where('status', 'active')
                            ->where('created_at', '>=', $sevenDaysAgo)
                            ->count(),
        'jumlahTutorialBaru' => Tutorial::where('status', 'approved')
                                        ->where('created_at', '>=', $sevenDaysAgo)
                                        ->count(),
        'jumlahTutorialPending' => Tutorial::where('status', 'pending')
                                   ->count(),

        'totalUsers' => User::count(),
    ]);
}


    // ========== USER MANAGEMENT ==========
    public function userManagement(Request $request)
{
    $query = User::query();

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('recent')) {
        $query->where('created_at', '>=', Carbon::now()->subDays(7));
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $sort = $request->get('sort');
    if ($sort === 'az') {
        $query->orderBy('name', 'asc');
    } elseif ($sort === 'za') {
        $query->orderBy('name', 'desc');
    }

    $users = $query->paginate(10);

    return view('admin.user-management', compact('users'));
}


    private function updateStatusDirect($id, $status, $message)
    {
        $user = User::findOrFail($id);
        $user->status = $status;
        $user->is_approved = $status === 'active';
        $user->save();

        return redirect()
            ->route('admin.user-management')
            ->with('approved', true)
            ->with('success', $message);
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->is_approved = true;
        $user->save();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Akun pengguna berhasil disetujui.');
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->is_approved = false;
        $user->save();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Akun pengguna berhasil ditolak.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // ========== TUTORIAL APPROVAL ==========
    public function tutorialApproval(Request $request)
{
    $query = Tutorial::with('user');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('recent')) {
        $query->where('created_at', '>=', Carbon::now()->subDays(7));
    }

    if ($request->filled('search')) {
        $query->where('judul', 'like', "%" . $request->search . "%");
    }

    $tutorials = $query->latest()->get();

    // Hitung jumlah berdasarkan status
    $jumlahTutorialDisetujui = Tutorial::where('status', 'approved')->count();
    $jumlahTutorialPending = Tutorial::where('status', 'pending')->count();
    $jumlahTutorialRejected = Tutorial::where('status', 'rejected')->count();
    $jumlahTutorialRevision = Tutorial::where('status', 'revision')->count();

    return view('admin.tutorial-approval', [
        'tutorials' => $tutorials,
        'jumlahTutorialDisetujui' => $jumlahTutorialDisetujui,
        'jumlahTutorialPending' => $jumlahTutorialPending,
        'jumlahTutorialRejected' => $jumlahTutorialRejected,
        'jumlahTutorialRevision' => $jumlahTutorialRevision,
    ]);
}



    public function pendingPosts()
    {
        $pendingPosts = Tutorial::where('status', 'pending')->get();
        return view('admin.pending-posts', compact('pendingPosts'));
    }

    public function showTutorialDetail($id)
    {
        $tutorial = Tutorial::with('user')->findOrFail($id);
        return view('admin.tutorial-detail', compact('tutorial'));
    }

    public function updateTutorialStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,revision',
            'points' => 'nullable|integer|min:0',
            'catatan_revisi' => 'nullable|string|max:1000',
        ]);

        $tutorial = Tutorial::findOrFail($id);
        $tutorial->status = $request->status;

        if ($request->status === 'revision' && $request->filled('catatan_revisi')) {
            $tutorial->catatan_revisi = $request->catatan_revisi;
        }

        $tutorial->save();

        if ($request->status === 'approved' && $request->filled('points')) {
            $user = $tutorial->user;
            $user->increment('points', $request->points);

            Reward::create([
                'user_id' => $user->id,
                'tutorial_id' => $tutorial->id,
                'poin' => $request->points,
            ]);
        }

        return redirect()->route('admin.tutorial-approval')->with('success', 'Status tutorial berhasil diperbarui.');
    }

    public function approve($postId)
    {
        $tutorial = Tutorial::findOrFail($postId);
        $tutorial->status = 'approved';
        $tutorial->save();

        return redirect()->route('admin.tutorial-approval')->with('success', 'Tutorial disetujui.');
    }

    public function reject($postId)
    {
        $tutorial = Tutorial::findOrFail($postId);
        $tutorial->status = 'rejected';
        $tutorial->save();

        return redirect()->route('admin.tutorial-approval')->with('success', 'Tutorial ditolak.');
    }

    public function tutorialBaru()
{
    $sevenDaysAgo = Carbon::now()->subDays(7);

    $tutorials = Tutorial::where('status', 'baru')
                    ->where('created_at', '>=', $sevenDaysAgo)
                    ->get();

    return view('admin.tutorial-approval', compact('tutorials'));
}

public function approvalIndex() {
    $sevenDaysAgo = Carbon::now()->subDays(7);

    $tutorials = Tutorial::where('created_at', '>=', $sevenDaysAgo)
                         ->latest()
                         ->get();

    return view('admin.tutorial-approval', compact('tutorials'));
}


}
