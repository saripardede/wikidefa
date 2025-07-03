@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold">Manajemen Pengguna</h2>

    {{-- FILTER, SEARCH --}}
    <form method="GET" class="flex flex-wrap gap-2 items-center mt-4" id="filterForm">
        <select name="status" class="border rounded px-3 py-1" id="statusSelect">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email" class="border rounded px-3 py-1 w-80" id="searchInput" />    
        <a href="{{ route('admin.user-management') }}" class="text-red-600 underline text-sm">Reset</a>
    </form>

    {{-- TABLE --}}
    <div class="overflow-auto rounded border mt-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-2">Nama</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t">
                        <td class="p-2">{{ $user->name }}</td>
                        <td class="p-2">{{ $user->email }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded text-white text-xs
                                {{ $user->status === 'active' ? 'bg-green-500' :
                                   ($user->status === 'pending' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="p-2">
                            {{-- Dropdown --}}
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" class="p-1 bg-gray-200 hover:bg-gray-300 rounded focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-32 bg-white border border-gray-300 rounded shadow-md z-50">
                                    @if($user->status === 'pending')
                                        <form method="POST" action="{{ route('admin.user.approve', $user->id) }}" class="approve-form">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-3 py-1 text-green-600 text-sm hover:bg-green-50">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.user.reject', $user->id) }}" class="reject-form">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-3 py-1 text-yellow-600 text-sm hover:bg-yellow-50">Tolak</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-3 py-1 text-red-600 text-sm hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">Tidak ada pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const filterForm = document.getElementById('filterForm');
    const statusSelect = document.getElementById('statusSelect');
    const searchInput = document.getElementById('searchInput');

    // Submit form otomatis ketika memilih status
    statusSelect.addEventListener('change', () => {
        filterForm.submit();
    });

    // Delay ketik pencarian lalu submit otomatis
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 600); // tunggu 600ms setelah user berhenti mengetik
    });

    // Konfirmasi swal untuk aksi
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: "Anda yakin ingin menghapus pengguna?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
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
</script>
@endpush
