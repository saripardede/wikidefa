@extends('layouts.admin')

@section('content')
<div class="p-6" x-data="{ open: false }">
    <h2 class="text-2xl font-bold mb-4">🏆 Sistem Reward</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-100">
                <tr class="text-left text-sm font-semibold text-gray-700">
                    <th class="px-4 py-3">Rank</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Skor</th>
                    <th class="px-4 py-3">Kontribusi</th>
                    <th class="px-4 py-3 flex items-center gap-1">
                        Level
                        <!-- Tombol untuk buka popup -->
                        <button @click="open = true" class="text-yellow-500 hover:scale-110 transition">
                            ⭐
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($users as $user)
                <tr class="{{ $user->rank <= 3 ? 'bg-yellow-100 font-semibold' : 'bg-white' }}">
                    <td class="px-4 py-3">{{ $user->rank }}</td>
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->role ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $user->skor ?? 0 }} poin</td>
                    <td class="px-4 py-3">{{ $user->tutorials_count ?? 0 }}</td>
                    <td class="px-4 py-3 font-bold">
                        {{ $user->level }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4 flex flex-wrap justify-between items-center">
        <p class="text-sm text-gray-500">Total Pengguna: {{ $users->total() }}</p>

        <div class="flex items-center space-x-2 mt-2 sm:mt-0">
            <span class="text-sm font-semibold">Page</span>
            @for ($i = 1; $i <= $users->lastPage(); $i++)
                <a href="{{ $users->url($i) }}">
                    <button class="px-2 py-1 text-xs rounded
                        {{ $users->currentPage() === $i ? 'bg-red-400 text-white font-bold' : 'bg-red-600 text-white hover:bg-red-700' }}">
                        {{ $i }}
                    </button>
                </a>
            @endfor

            <!-- Tombol View All -->
            <a href="{{ route('admin.reward.index', ['per_page' => $users->total()]) }}">
                <button class="px-3 py-1 bg-red-700 text-white text-xs font-semibold rounded hover:bg-red-800">
                    View All
                </button>
            </a>
        </div>
    </div>
</div>
@endsection
