@extends('layouts.user')

@section('content')
    <h2 class="text-3xl font-bold mb-6">Status Kontribusi Saya</h2>

    <div class="bg-white shadow-md rounded overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 font-bold mb-6 text-left">
                <tr>
                    <th class="px-6 py-3 text-base font-bold text-gray-900">No</th>
                    <th class="px-6 py-3 text-base font-bold text-gray-900">Tanggal</th>
                    <th class="px-6 py-3 text-base font-bold text-gray-900">Judul</th>
                    <th class="px-6 py-3 text-base font-bold text-gray-900">Kategori</th>
                    <th class="px-6 py-3 text-base font-bold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-base font-bold text-gray-900">Catatan Revisi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                @forelse ($tutorials as $information => $tutorial)
                    <tr>
                        <td class="px-6 py-4">{{ $information + 1 }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($tutorial->created_at)->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if ($tutorial->status === 'approved')
                                <a href="{{ route('kategori-postingan', ['kategori' => $tutorial->kategori, 'judul' => $tutorial->judul]) }}" class="text-blue-600 hover:underline">
                                    {{ $tutorial->judul }}
                                </a>


                            @elseif ($tutorial->status === 'revision')
                                <a href="{{ route('tutorial.edit', $tutorial->id) }}" class="text-blue-600 hover:underline">
                                    {{ $tutorial->judul }}
                                </a>
                            @else
                                {{ $tutorial->judul }}
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $tutorial->kategori }}</td>
                        <td class="px-6 py-4">
                            @switch($tutorial->status)
                                @case('pending')
                                    <span class="text-yellow-500 font-semibold">Pending</span>
                                    @break
                                @case('approved')
                                    <span class="text-green-600 font-semibold">Disetujui</span>
                                    @break
                                @case('revision')
                                    <span class="text-blue-500 font-semibold">Revisi</span>
                                    @break
                                @case('rejected')
                                    <span class="text-red-600 font-semibold">Ditolak</span>
                                    @break
                                @default
                                    <span class="text-gray-500">Tidak diketahui</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            @if ($tutorial->status === 'revision')
                                {{ $tutorial->catatan_revisi ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada kontribusi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
