@extends('admin.layout') {{-- Ganti jika kamu pakai layout lain --}}

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Tutorial Menunggu Persetujuan</h1>

        @if ($pendingTutorials->count())
            @foreach($pendingTutorials as $tutorial)
                <div class="bg-white shadow rounded p-4 mb-4">
                    <h3 class="text-xl font-semibold">{{ $tutorial->judul }}</h3>
                    <p class="text-sm text-gray-500 mb-2">Kategori: {{ $tutorial->kategori }}</p>
                    <p class="mb-3">{{ Str::limit($tutorial->isi, 100) }}</p>
                    
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.approve', $tutorial->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Approved</button>
                        </form>

                        <form action="{{ route('admin.reject', $tutorial->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Rejected</button>
                        </form>
                        
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-gray-600">Tidak ada tutorial yang menunggu persetujuan.</p>
        @endif
    </div>
@endsection
