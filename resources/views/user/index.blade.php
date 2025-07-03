@extends('layouts.user')

@section('content')
<main class="space-y-6 pr-80">
    <!-- Daftar Tutorial -->
    @foreach($tutorials as $tutorial)
        <div class="border p-4 rounded mb-3 bg-white shadow">

            <!-- Header: Nama User & Tanggal -->
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                        </svg>
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold flex items-center">
                            {{ $tutorial->user->name ?? 'User' }}
                            <span class="ml-1 text-yellow-400">⭐</span>
                        </p>
                        <p class="text-gray-500 text-xs">
                            Post on {{ $tutorial->updated_at->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kategori -->
            <span class="inline-block bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded-full mb-2">
                {{ $tutorial->kategori }}
            </span>

            <!-- Judul -->
            <h2 class="text-xl font-bold">{{ $tutorial->judul }}</h2>

           <!-- Deskripsi -->
            @php
                $items = json_decode($tutorial->isi, true);
                $firstDeskripsi = is_array($items) && count($items) > 0 ? $items[0]['deskripsi'] : 'Tidak ada deskripsi.';
            @endphp


           @if(is_array($items))
            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="relative">
                        <h4 class="font-semibold text-lg mb-1">Tutorial {{ $index + 1 }}</h4>

                        <!-- Deskripsi Ringkas -->
                        <div class="text-gray-800">
                            <p id="deskripsi-{{ $tutorial->id }}-{{ $index }}" class="whitespace-pre-line line-clamp-4">
                                {{ $item['deskripsi'] }}
                            </p>
                            <a href="javascript:void(0)" onclick="toggleClamp('{{ $tutorial->id }}-{{ $index }}')" id="toggle-btn-{{ $tutorial->id }}-{{ $index }}" class="text-blue-600 hover:underline text-sm mt-1 block">
                                Baca Selengkapnya
                            </a>
                        </div>

                        <!-- Deskripsi Penuh -->
                        <div id="deskripsi-penuh-{{ $tutorial->id }}-{{ $index }}" class="hidden whitespace-pre-line mt-2 text-gray-800">
                            {{ $item['deskripsi'] }}
                            <a href="javascript:void(0)" onclick="toggleDeskripsi('{{ $tutorial->id }}-{{ $index }}')" class="text-blue-600 hover:underline text-sm mt-1 block">Tampilkan Lebih Sedikit</a>
                        </div>

                @if(!empty($item['media']))
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($item['media'] as $media)
                            @if(Str::endsWith($media, ['.jpg', '.jpeg', '.png', '.gif']))
                                <img src="{{ asset('storage/' . $media) }}" 
                                alt="gambar" 
                                class="h-22 w-auto max-w-sm cursor-zoom-in rounded border my-2"
                                onclick="zoomImage('{{ asset('storage/' . $media) }}')" />
                            @elseif(Str::endsWith($media, ['.mp4', '.avi', '.mov']))
                                <video controls class="h-32 rounded border">
                                    <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                </video>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div> 
@else
    <p class="text-gray-500">Tidak ada isi tutorial yang ditemukan.</p>
@endif

    <!-- Modal Zoom -->
            <div id="zoom-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
                <span onclick="closeZoom()"
                    class="absolute top-4 right-6 z-10 text-white bg-black bg-opacity-50 px-3 py-1 rounded-full text-3xl cursor-pointer hover:bg-opacity-80 transition">
                    &times;
                </span>
                <img id="zoom-image" src="" class="max-h-[90%] max-w-[90%] rounded shadow-lg" />
            </div>
        </div>
    @endforeach

    <!-- Leaderboard -->
    <div class="fixed top-20 right-6 w-72 bg-gray-900 text-white p-4 rounded-xl shadow-xl z-10">
        <h2 class="text-xl font-bold">Leaderboard</h2>
        <ol class="mt-2 space-y-1 text-sm">
            @foreach ($topUsers as $index => $user)
                <li>
                    {{ $index + 1 }}. {{ $user->name ?? 'none' }} - {{ $user->poin }} pts - {{ $user->tanggal }}
                </li>
            @endforeach
        </ol>
        <a href="{{ route('user.leaderboard') }}" class="mt-4 w-full inline-block text-center bg-blue-600 hover:bg-blue-700 py-2 rounded">
            Tampilkan
        </a>
    </div>

</main>

<!-- Script Toggle -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="deskripsi-ringkas-"]').forEach(el => {
        const id = el.id.replace('deskripsi-ringkas-', '');
        const text = el.textContent || '';
        
        // Hitung jumlah kalimat (asumsikan tiap kalimat diakhiri titik)
        const jumlahKalimat = text.split(/[.!?]\s/).length;

        // Tampilkan toggle hanya jika lebih dari 4 kalimat
        if (jumlahKalimat > 4) {
             const tombol = document.getElementById(`toggle-btn-${id}`);
            if (tombol) tombol.classList.remove('hidden');
        }
    });
});

  function toggleDeskripsi(id) {
    const ringkas = document.getElementById('deskripsi-ringkas-' + id);
    const penuh = document.getElementById('deskripsi-penuh-' + id);
    const toggleBtn = document.getElementById('toggle-btn-' + id);

    if (!ringkas || !penuh || !toggleBtn) return;

    const isHidden = penuh.classList.contains('hidden');

    if (isHidden) {
        penuh.classList.remove('hidden');
        ringkas.classList.add('hidden');
        toggleBtn.classList.add('hidden'); // Sembunyikan tombol awal
    } else {
        penuh.classList.add('hidden');
        ringkas.classList.remove('hidden');
        toggleBtn.classList.remove('hidden'); // Tampilkan kembali tombol awal
    }
}

function zoomImage(src) {
      const modal = document.getElementById('zoom-modal');
      const img = document.getElementById('zoom-image');
      img.src = src;
      modal.classList.remove('hidden');
  }

  function closeZoom() {
      document.getElementById('zoom-modal').classList.add('hidden');
  }
</script>
<style>
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
