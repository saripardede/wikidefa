@extends('layouts.user')

@section('content')
<div class="bg-gray-800 min-h-screen py-10 px-4 text-white">
    <div class="max-w-6xl mx-auto bg-[#1f2937] rounded-xl shadow-lg p-6">
        <h1 class="text-4xl font-bold text-red-400 mb-8 flex items-center">
            <span class="mr-3">Leaderboard 🏆</span>
        </h1>

        


        {{-- Tabel Leaderboard Lengkap --}}
        <div class="overflow-x-auto rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#374151] text-white">
                    <tr class="text-sm uppercase tracking-wider">
                        <th class="py-3 px-4 border-b border-gray-600">Rank</th>
                        <th class="py-3 px-4 border-b border-gray-600">Username</th>
                        <th class="py-3 px-4 border-b border-gray-600">Role</th>
                        <th class="py-3 px-4 border-b border-gray-600">Score</th>
                        <th class="py-3 px-4 border-b border-gray-600">Kontribusi</th>
                        <th class="py-3 px-4 border-b border-gray-600">Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr class="bg-[#111827] hover:bg-[#1e293b] border-b border-gray-700 text-sm">
                        <td class="py-3 px-4 font-semibold text-white">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 flex items-center text-white">
                            <div class="w-8 h-8 bg-gray-700 text-white flex items-center justify-center rounded-full mr-2">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            {{ $user->name }}
                        </td>
                        <td class="py-3 px-4 text-white">{{ $user->role }}</td>
                        <td class="py-3 px-4 text-white">{{ $user->skor ?? 0 }} points</td>
                        <td class="py-3 px-4 text-white">{{ $user->tutorials_count ?? 0 }}</td>
                        <td class="py-3 px-4 text-white">Level {{ $user->level }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex justify-center items-center space-x-2">
            @php
                $current = request()->get('page', 1);
                $perPage = $perPage ?? 10;
                $total = $total ?? $users->count();
                $totalPages = $perPage > 0 ? ceil($total / $perPage) : 1;
            @endphp

            @for ($i = 1; $i <= $totalPages; $i++)
                <a href="?page={{ $i }}"
                   class="px-3 py-1 rounded {{ $i == $current ? 'bg-red-600 text-white font-semibold' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                   {{ $i }}
                </a>
            @endfor
        </div>
    </div>
</div>
@endsection

