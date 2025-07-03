@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow w-full max-w-xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Tambah Reward</h2>

    <form action="{{ route('admin.reward.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-medium mb-1">Nama Reward</label>
            <input type="text" name="nama" class="w-full border px-3 py-2 rounded" value="{{ old('nama') }}" required>
            @error('nama') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border px-3 py-2 rounded">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Poin</label>
            <input type="number" name="poin" class="w-full border px-3 py-2 rounded" value="{{ old('poin') }}" required>
            @error('poin') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
