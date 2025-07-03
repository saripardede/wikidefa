@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-black text-4xl font-bold mb-6">Daftar Kategori</h2>

    @php
        $categories = [
            ['name' => 'Baterai', 'image' => 'baterai.jpg'],
            ['name' => 'Genset', 'image' => 'genset.jpg'],
            ['name' => 'AC', 'image' => 'ac.jpg'],
        ];
    @endphp

    @foreach($categories as $category)
        <div class="bg-gray-100 rounded-lg shadow-md p-4 mb-6">
            <h3 class="text-xl font-bold mb-2 capitalize">{{ $category['name'] }}</h3>
            <hr class="mb-3">

            <div class="flex justify-between items-center">
                <img src="{{ asset('storage/images/' . $category['image']) }}"
                     alt="{{ $category['name'] }}"
                     class="w-32 h-24 object-cover rounded-md">

                <a href="{{ route('user.kategori.show', ['id' => $category['name']]) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Tampilkan
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
