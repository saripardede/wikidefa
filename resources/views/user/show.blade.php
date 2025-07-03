// untuk informasi user 
@extends('layouts.user')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded p-6">
        <h2 class="text-2xl font-bold mb-4">{{ $tutorial->judul }}</h2>
        <p class="mb-4 text-gray-800 whitespace-pre-line">{{ $tutorial->deskripsi }}</p>

        @if($tutorial->media->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                @foreach($tutorial->media as $media)
                    <img src="{{ asset('storage/' . $tutorial->image_path) }}"
                         alt="Media Tutorial" class="w-full h-auto rounded shadow">
                @endforeach
            </div>
        @endif

        <p class="text-sm text-gray-600">Ditulis oleh: {{ $tutorial->user->name ?? 'Seseorang' }} | {{ $tutorial->created_at->format('d F Y') }}</p>
    </div>
</div>
@endsection


//COBA AJA
@extends('layouts.user')

@section('content')
    <h2 class="text-3xl font-bold mb-4">{{ $tutorial->judul }}</h2>
    <p class="text-gray-500 mb-2">Kategori: {{ $tutorial->kategori }}</p>
    <p class="text-sm text-gray-400 mb-4">Tanggal dibuat: {{ $tutorial->created_at->format('d M Y') }}</p>

    <div class="prose max-w-full">
        {!! $tutorial->isi !!} {{-- Pastikan isi tutorial di-render sebagai HTML jika menggunakan WYSIWYG editor --}}
    </div>
@endsection
