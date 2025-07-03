@extends('layouts.admin')

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
<div class="container mx-auto px-4 py-6 max-w-5xl">
    <div class="bg-white p-6 rounded-xl shadow-md space-y-6">

        {{-- Judul --}}
        <h1 class="text-3xl font-bold text-green-700">{{ $tutorial->judul }}</h1>

        {{-- Informasi --}}
        <div class="text-sm text-gray-700 space-y-1">
            <p><strong>Penulis:</strong> {{ $tutorial->user->name ?? 'Tidak diketahui' }}</p>
            <p><strong>Kategori:</strong> {{ $tutorial->kategori }}</p>
            <p><strong>Tanggal Dibuat:</strong> {{ $tutorial->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Status:</strong>
                @switch($tutorial->status)
                    @case('approved') <span class="text-green-600">Disetujui</span> @break
                    @case('revision') <span class="text-yellow-600">Perlu Revisi</span> @break
                    @case('rejected') <span class="text-red-600">Ditolak</span> @break
                    @default <span class="text-gray-600">Pending</span>
                @endswitch
            </p>
        </div>

        {{-- Isi Tutorial --}}
        @php
            $items = json_decode($tutorial->isi, true);
        @endphp

        @if($items)
            @foreach($items as $index => $item)
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-2">Tutorial {{ $index + 1 }}</h3>
                    <p class="whitespace-pre-line mb-2">{{ $item['deskripsi'] }}</p>

                    @if(!empty($item['media']))
                        <div class="flex flex-wrap gap-2">
                            @foreach($item['media'] as $media)
                                @if(Str::endsWith($media, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $media) }}" 
                                alt="gambar" 
                                class="h-22 w-auto max-w-sm cursor-zoom-in rounded border my-2"
                                onclick="zoomImage('{{ asset('storage/' . $media) }}')" />
                                @elseif(Str::endsWith($media, ['.mp4', '.avi', '.mov']))
                                    <video controls class="h-32 border rounded">
                                        <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                        Browser tidak mendukung video.
                                    </video>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-gray-500">Tidak ada isi tutorial yang ditemukan.</p>
        @endif

        {{-- Catatan --}}
        @if($tutorial->status === 'revision' && $tutorial->catatan_revisi)
            <div class="bg-yellow-100 p-3 rounded border border-yellow-400 mb-6">
                <strong>Catatan:</strong>
                <p>{{ $tutorial->catatan_revisi }}</p>
            </div>
        @endif

        {{-- Aksi Admin --}}
        @auth
            @if(auth()->user()->role === 'admin')
                <form id="statusForm" action="{{ route('admin.tutorial.updateStatus', $tutorial->id) }}" method="POST" class="mt-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="statusInput">
                    <input type="hidden" name="catatan_revisi" id="noteInput">

                    <div class="flex flex-wrap gap-2 mt-4">
                        {{-- Tombol Kembali --}}
                        <a href="{{ url()->previous() }}" class="inline-block bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">← Kembali</a>

                        @if($tutorial->status === 'pending')
                            <button type="button" onclick="openApprovalPopup()" class="bg-green-500 px-4 py-2 rounded hover:bg-green-600 text-white">Setujui</button>
                            <button type="button" onclick="openRevisionPopup()" class="bg-yellow-400 px-4 py-2 rounded hover:bg-yellow-500 text-white">Revisi</button>
                            <button type="button" onclick="openRejectPopup()" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 text-white">Tolak</button>
                        @elseif($tutorial->status === 'approved' || $tutorial->status === 'revision')
                            <button type="button" onclick="openNotePopup()" class="bg-yellow-400 px-4 py-2 rounded hover:bg-yellow-500 text-white">Ubah Status</button>
                        @endif
                    </div>
                </form>
            @endif
        @endauth
    </div>
</div>

{{-- Popup Setujui --}}
<div id="confirmPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded shadow-md text-center w-full max-w-md">
        <p class="mb-4 font-semibold">Apakah Anda yakin ingin menyetujui tutorial ini?</p>
        <p class="mb-2">Berikan Point:</p>
        <div class="mb-4 text-left space-y-2">
            <label><input type="radio" name="point" value="10"> ✨ = 10 point</label><br>
            <label><input type="radio" name="point" value="20"> ✨✨ = 20 point</label><br>
            <label><input type="radio" name="point" value="30"> ✨✨✨ = 30 point</label><br>
            <input type="number" id="customPoint" class="mt-2 border p-1 rounded w-full" placeholder="Poin khusus..." min="1">
        </div>
        <div class="flex justify-end gap-2">
            <button onclick="submitApproval()" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
            <button onclick="closeApprovalPopup()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
        </div>
    </div>
</div>

{{-- Popup Revisi --}}
<div id="revisionPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl shadow-md text-center w-full max-w-sm space-y-4">
        <h2 class="text-xl font-bold text-yellow-600">Revisi Tutorial</h2>

        <textarea id="revisionNote" class="w-full border rounded p-2 h-24" placeholder="Tuliskan alasan revisi untuk penulis:"></textarea>

        <div class="flex justify-end gap-2 pt-2">
            <button onclick="submitRevision()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Kirim Revisi</button>
            <button onclick="closeRevisionPopup()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
        </div>
    </div>
</div>


{{-- Popup Konfirmasi Tolak --}}
<div id="rejectPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl shadow-md text-center w-full max-w-sm space-y-4">
        <h2 class="text-xl font-bold text-red-600">Tolak Tutorial</h2>
        <p class="text-gray-700">Apakah Anda yakin ingin menolak tutorial ini?</p>

        <div class="flex justify-end gap-2 pt-4">
            <button onclick="submitReject()" class="bg-red-500 text-white px-4 py-2 rounded">Ya, Tolak</button>
            <button onclick="closeRejectPopup()" class="bg-blue-500 text-white px-4 py-2 rounded">Batal</button>
        </div>
    </div>
</div>


{{-- Popup Ubah Status --}}
<div id="notePopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl shadow-md text-center w-full max-w-sm space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Ubah Status Tutorial</h2>
        <p class="text-gray-600">Ingin mengubah status persetujuan tutorial ?</p>

        <div class="space-y-2">
            <button onclick="openRevisionNotePopup()" class="w-full bg-gray-200 hover:bg-blue-500 text-gray-400 font-semibold py-2 rounded">Revisi</button>
            <button onclick="openApprovalPopup()" class="w-full bg-gray-200 hover:bg-blue-500 text-gray-400 font-semibold py-2 rounded">Setujui</button>
            <button onclick="openRejectPopup()" class="w-full bg-gray-200 hover:bg-blue-500 text-gray-400 font-semibold py-2 rounded">Tolak</button>
        </div>

        <div class="pt-4">
            <button onclick="closeNotePopup()" class="text-sm text-gray-500 hover:underline font-semibold">Batal</button>
        </div>
    </div>
</div>


{{-- Popup Catatan Revisi --}}
<div id="revisionNotePopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl shadow-md text-center w-full max-w-sm space-y-4">
        <h2 class="text-xl font-bold">Catatan Revisi</h2>
        <textarea id="revisionNoteInput" rows="4" class="w-full border rounded p-2" placeholder="Masukkan catatan revisi..."></textarea>
        <div class="flex justify-end gap-2">
            <button onclick="submitRevisionNote()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
            <button onclick="closeRevisionNotePopup()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
        </div>
    </div>
</div>

<!-- Modal Zoom -->
            <div id="zoom-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
                <span onclick="closeZoom()"
                    class="absolute top-4 right-6 z-10 text-white bg-black bg-opacity-50 px-3 py-1 rounded-full text-3xl cursor-pointer hover:bg-opacity-80 transition">
                    &times;
                </span>
                <img id="zoom-image" src="" class="max-h-[90%] max-w-[90%] rounded shadow-lg" />
            </div>
        </div>
@endsection

@push('scripts')
<script>
    function openApprovalPopup() {
        closeNotePopup();
        document.getElementById('confirmPopup').classList.remove('hidden');
    }

    function closeApprovalPopup() {
        document.getElementById('confirmPopup').classList.add('hidden');
    }

    function submitApproval() {
        let point = null;
        document.querySelectorAll('input[name="point"]').forEach(radio => {
            if (radio.checked) point = radio.value;
        });

        const custom = parseInt(document.getElementById('customPoint').value);
        if (!point && custom > 0) point = custom;

        if (!point || isNaN(point) || point <= 0) {
            alert('Silakan pilih atau masukkan jumlah poin yang valid.');
            return;
        }

        document.getElementById('noteInput').value = `Poin diberikan: ${point}`;
        document.getElementById('statusInput').value = 'approved';
        document.getElementById('statusForm').submit();
    }

    function openNotePopup() {
        document.getElementById('notePopup').classList.remove('hidden');
    }

    function closeNotePopup() {
        document.getElementById('notePopup').classList.add('hidden');
    }

    function submitNotePopup(status) {
        document.getElementById('statusInput').value = status;
        document.getElementById('statusForm').submit();
    }

    //POP UP UNTUK REVISI
    function openRevisionPopup() {
        closeNotePopup();
        document.getElementById('revisionPopup').classList.remove('hidden');
    }

    function closeRevisionPopup() {
        document.getElementById('revisionPopup').classList.add('hidden');
    }

    function submitRevision() {
        const note = document.getElementById('revisionNote').value.trim();
        if (note === '') {
            alert('Catatan revisi wajib diisi.');
            return;
        }
        document.getElementById('noteInput').value = note;
        document.getElementById('statusInput').value = 'revision';
        document.getElementById('statusForm').submit();
    }

    //POP UP UNTUK TOLAK
    function openRejectPopup() {
        closeNotePopup(); // Tutup popup lain jika terbuka
        document.getElementById('rejectPopup').classList.remove('hidden');
    }

    function submitReject() {
        document.getElementById('statusInput').value = 'rejected';
        document.getElementById('noteInput').value = 'Ditolak oleh admin.';
        document.getElementById('statusForm').submit();
    }

    function closeRejectPopup() {
        document.getElementById('rejectPopup').classList.add('hidden');
    }

    function openRevisionNotePopup() {
        closeNotePopup();
        document.getElementById('revisionNotePopup').classList.remove('hidden');
    }

    function closeRevisionNotePopup() {
        document.getElementById('revisionNotePopup').classList.add('hidden');
    }

    function submitRevisionNote() {
        const note = document.getElementById('revisionNoteInput').value.trim();
        if (note === '') {
            alert('Catatan revisi wajib diisi.');
            return;
        }
        document.getElementById('noteInput').value = note;
        document.getElementById('statusInput').value = 'revision';
        document.getElementById('statusForm').submit();
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
@endpush
