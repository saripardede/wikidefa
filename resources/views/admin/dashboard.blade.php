@extends('layouts.admin')

@section('content')
<div x-data="{ showProfile: false }">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Dashboard</h2>
        <div class="flex items-center space-x-4">
            <i class="fas fa-user-circle text-2xl cursor-pointer hover:text-blue-500" @click="showProfile = true"></i>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Info Boxes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('admin.user-management', ['status' => 'active', 'recent' => 1]) }}">
            <div class="bg-blue-100 p-4 rounded-lg shadow hover:shadow-md">
                <p class="text-gray-600">Pengguna Baru</p>
                <p class="text-xl font-bold text-blue-700">{{ $jumlahPenggunaBaru }}</p>
            </div>
        </a>
        
        <a href="{{ route('admin.tutorial-approval', ['status' => 'approved']) }}">
            <div class="bg-green-100 p-4 rounded-lg shadow hover:shadow-md">
                <p class="text-gray-600">Tutorial Baru</p>
                <p class="text-xl font-bold text-blue-700">{{ $jumlahTutorialBaru }}</p>
            </div>
        </a>

        <a href="{{ route('admin.tutorial-approval', ['status' => 'pending', 'recent' => 1]) }}">
            <div class="bg-yellow-100 p-4 rounded-lg shadow hover:shadow-md">
                <p class="text-gray-600">Tutorial Pending</p>
                <p class="text-xl font-bold text-green-700">{{ $jumlahTutorialPending }}</p>
            </div>
        </a>

    </div>

    <!-- Container Notifikasi -->
<div class="bg-yellow-100 text-black p-4 rounded-lg mb-4">
    <h3 class="text-2xl font-bold mb-2 flex items-center">
    <i class="fas fa-bell text-yellow-500 mr-2"></i> Notifikasi Admin
</h3>


    <div class="space-y-3 mt-4">
        {{-- Notifikasi Akun Pending --}}
        @forelse ($pendingUsers as $user)
            <div class="bg-blue-100 border-l-4 border-blue-400 p-4 flex items-center justify-between rounded-lg">
                <p>{{ $user->name }} meminta persetujuan akun.</p>
                <div class="space-x-2">
                    <form action="{{ route('admin.user.approve', $user->id) }}" method="POST" class="approve-form inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.user.reject', $user->id) }}" method="POST" class="reject-form inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Tidak ada notifikasi akun baru.</p>
        @endforelse

        {{-- Notifikasi Tutorial Pending --}}
        @forelse ($pendingTutorials as $tutorial)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 flex items-center justify-between rounded-lg">
                <p>
                    Tutorial <strong>{{ $tutorial->title }}</strong> oleh <strong>{{ $tutorial->user->name }}</strong> menunggu persetujuan.
                </p>
                <div class="space-x-2 flex">
                    <a href="{{ route('admin.tutorial.detail', $tutorial->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                        <i class="fas fa-eye"></i> Tinjau
                    </a>
                    <form action="{{ route('admin.tutorial.approval', $tutorial->id) }}" method="POST" class="approve-tutorial-form inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.tutorial.reject', $tutorial->id) }}" method="POST" class="reject-tutorial-form inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Tidak ada tutorial pending saat ini.</p>
        @endforelse
    </div>
</div>


    <!-- Modal Profil -->
    <div x-show="showProfile" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-xl relative">
            <h2 class="text-3xl font-bold mb-6">Profil</h2>

            <div class="flex flex-col items-center mb-6">
                <i class="fas fa-user-circle text-7xl text-gray-400 mb-2"></i>
                <h3 class="text-xl font-semibold">{{ Auth::user()->username }}</h3>
            </div>


            <div class="space-y-4">
                <div>
                    <label class="text-gray-600 block mb-1">Username</label>
                    <input type="text" value="{{ Auth::user()->username }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="text-gray-600 block mb-1">Role</label>
                    <input type="text" value="{{ Auth::user()->role }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="text-gray-600 block mb-1">Email</label>
                    <input type="text" value="{{ Auth::user()->email }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="text-gray-600 block mb-1">Last Login</label>
                    <input type="text" value="{{ \Carbon\Carbon::parse(Auth::user()->last_login)->translatedFormat('d F Y') }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="text-gray-600 block mb-1">Tanggal Registrasi</label>
                    <input type="text" value="{{ \Carbon\Carbon::parse(Auth::user()->created_at)->translatedFormat('d F Y') }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                </div>
            </div>

            <button @click="showProfile = false" class="mt-6 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//unpkg.com/alpinejs" defer></script>

<script>
    // Script untuk akun
    document.querySelectorAll('.reject-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tolak Akun?',
                text: "Kamu yakin ingin menolak akun ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Setujui Akun?',
                text: "Kamu yakin ingin menyetujui akun ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#38a169',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Script untuk tutorial
    document.querySelectorAll('.reject-tutorial-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tolak Tutorial?',
                text: "Kamu yakin ingin menolak tutorial ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.approve-tutorial-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Setujui Tutorial?',
                text: "Kamu yakin ingin menyetujui tutorial ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#38a169',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Flash message untuk approval akun (jika ada)
    @if(session('approved'))
        Swal.fire({
            title: 'Berhasil!',
            text: 'Akun pengguna berhasil disetujui.',
            icon: 'success',
            confirmButtonColor: '#10b981'
        });

        window.onload = () => {
            const target = document.getElementById('manajemen-pengguna');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        };
    @endif
</script>
@endpush
