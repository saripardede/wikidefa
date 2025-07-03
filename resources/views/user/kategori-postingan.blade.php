@extends('layouts.user')

@section('content')
<div class="bg-gray-800 min-h-screen py-6">
    <div class="container mx-auto p-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
            <h2 class="text-white text-2xl font-bold">📂 Kategori: {{ $kategori }}</h2>
            <form action="{{ route('kategori.search', ['kategori' => $kategori]) }}" method="GET" class="flex w-full md:w-1/2">
                <input type="text" name="q" placeholder="Search About {{ $kategori }}..."
                    class="px-4 py-2 rounded-l-md border border-gray-300 focus:outline-none w-full">
                <button type="submit"
                    class="bg-gray-900 text-white px-4 rounded-r-md hover:bg-gray-700">
                    🔍
                </button>
            </form>
        </div>

        @if($tutorials->count() === 1 && request()->routeIs('kategori.tutorial.show'))
            @php $tutorial = $tutorials->first(); @endphp
            <div id="tutorial-{{ $tutorial->id }}" class="border p-4 rounded mb-6 bg-white shadow flex">
                <div class="w-1/4 pr-4 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center mb-2">
                        <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-800">{{ $tutorial->user->name ?? 'User' }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        Post on {{ \Carbon\Carbon::parse($tutorial->updated_at)->format('d F Y') }}
                    </p>
                </div>

                <div class="w-3/4">
                    <h3 class="text-xl font-bold mb-4">{{ $tutorial->judul }}</h3>

                    @php
                        $items = json_decode($tutorial->isi, true);
                    @endphp

                    @if(is_array($items))
                        <div class="space-y-4">
                            @foreach($items as $index => $item)
                                <div class="relative">
                                    <h4 class="font-semibold text-lg mb-1">Tutorial {{ $index + 1 }}</h4>
                                    <div id="deskripsi-{{ $tutorial->id }}-{{ $index }}" class="text-gray-800 line-clamp-2">
                                        <p class="whitespace-pre-line">{{ $item['deskripsi'] }}</p>
                                    </div>
                                    <a href="javascript:void(0)" onclick="toggleDeskripsi('{{ $tutorial->id }}-{{ $index }}')" id="toggle-btn-{{ $tutorial->id }}-{{ $index }}" class="text-blue-600 hover:underline text-sm mt-1 block">Baca Selengkapnya</a>

                                    @if(!empty($item['media']))
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @foreach($item['media'] as $media)
                                                @if(Str::endsWith($media, ['.jpg', '.jpeg', '.png', '.gif']))
                                                    <img src="{{ asset('storage/' . $media) }}" alt="Gambar" class="h-32 rounded border">
                                                @elseif(Str::endsWith($media, ['.mp4', '.avi', '.mov']))
                                                    <video controls class="h-32 rounded border">
                                                        <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                                        Browser Anda tidak mendukung video.
                                                    </video>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Tidak ada deskripsi tutorial yang ditemukan.</p>
                    @endif
                </div>
            </div>
        @elseif($tutorials->count() > 0)
            @foreach($tutorials as $tutorial)
                <div id="tutorial-{{ $tutorial->id }}" class="border p-4 rounded mb-6 bg-white shadow flex">
                    {{-- Gunakan blok isi tutorial seperti di atas --}}
                    {{-- (Copy paste dari blok tunggal atau ekstrak ke partial jika perlu) --}}
                    <div class="w-1/4 pr-4 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">{{ $tutorial->user->name ?? 'User' }}</p>
                        <p class="text-gray-500 text-xs mt-1">
                            Post on {{ \Carbon\Carbon::parse($tutorial->updated_at)->format('d F Y') }}
                        </p>
                    </div>

                    <div class="w-3/4">
                        <h3 class="text-xl font-bold mb-4">{{ $tutorial->judul }}</h3>

                        @php
                            $items = json_decode($tutorial->isi, true);
                        @endphp

                        @if(is_array($items))
                            <div class="space-y-4">
                                @foreach($items as $index => $item)
                                    <div class="relative">
                                        <h4 class="font-semibold text-lg mb-1">Tutorial {{ $index + 1 }}</h4>

                                        <div id="deskripsi-{{ $tutorial->id }}-{{ $index }}" class="text-gray-800 line-clamp-2">
                                            <p class="whitespace-pre-line">{{ $item['deskripsi'] }}</p>
                                        </div>

                                        <a href="javascript:void(0)" onclick="toggleDeskripsi('{{ $tutorial->id }}-{{ $index }}')" id="toggle-btn-{{ $tutorial->id }}-{{ $index }}" class="text-blue-600 hover:underline text-sm mt-1 block">Baca Selengkapnya</a>

                                        @if(!empty($item['media']))
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach($item['media'] as $media)
                                                    @if(Str::endsWith($media, ['.jpg', '.jpeg', '.png', '.gif']))
                                                        <img src="{{ asset('storage/' . $media) }}" alt="Gambar" class="h-32 rounded border">
                                                    @elseif(Str::endsWith($media, ['.mp4', '.avi', '.mov']))
                                                        <video controls class="h-32 rounded border">
                                                            <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                                            Browser Anda tidak mendukung video.
                                                        </video>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada deskripsi tutorial yang ditemukan.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-gray-300 text-center">Belum ada tutorial dalam kategori ini.</p>
        @endif
    </div>
</div>

<script>
    function toggleDeskripsi(id) {
        const deskripsi = document.getElementById('deskripsi-' + id);
        const tombol = document.getElementById('toggle-btn-' + id);

        if (deskripsi.classList.contains('line-clamp-2')) {
            deskripsi.classList.remove('line-clamp-2');
            tombol.textContent = 'Tutup';
        } else {
            deskripsi.classList.add('line-clamp-2');
            tombol.textContent = 'Baca Selengkapnya';
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const hash = window.location.hash;
        if (hash.startsWith("#highlight-")) {
            const id = hash.replace("#highlight-", "");
            const el = document.getElementById("tutorial-" + id);
            if (el) {
                el.classList.add("flash-highlight");
                el.scrollIntoView({ behavior: "smooth", block: "center" });
                setTimeout(() => {
                    el.classList.remove("flash-highlight");
                }, 3000);
            }
        }
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .flash-highlight {
        background-color: #bfdbfe !important;
        box-shadow: 0 0 20px 6px rgba(59, 130, 246, 0.7);
        transition: all 0.3s ease-in-out;
    }
</style>
@endsection
