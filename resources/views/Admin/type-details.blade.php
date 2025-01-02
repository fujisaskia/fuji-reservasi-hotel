<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Room Type Detail | Admin')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg text-sm md:text-xs">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center pb-2 border-b font-playfair">{{ $roomType->tipe_kamar }}</h2>

        <!-- Foto Tipe Kamar -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-4">
            @php
                $fotos = json_decode($roomType->foto); // Decode JSON path foto
            @endphp
            @if ($fotos)
                @foreach ($fotos as $foto)
                <img src="{{ asset('storage/' . $foto) }}" alt="Room Image" class="rounded-t-lg w-1/2 h-60 object-cover">
                @endforeach
            @else
                <img src="https://via.placeholder.com/400x250" alt="Room Image" class="rounded-lg w-1/2 h-60 object-cover">
            @endif
            </div>
        </div>

        <!-- Detail Informasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Tipe Kamar -->
            <div>
            <h3 class="text-base md:text-sm font-semibold text-gray-700">Tipe Kamar</h3>
            <p class="text-gray-600">{{ $roomType->tipe_kamar }}</p>
            </div>
            <!-- Kapasitas -->
            <div>
            <h3 class="text-base md:text-sm font-semibold text-gray-700">Kapasitas</h3>
            <p class="text-gray-600">{{ $roomType->kapasitas }} Orang</p>
            </div>
            <!-- Harga -->
            <div>
            <h3 class="text-base md:text-sm font-semibold text-gray-700">Harga</h3>
            <p class="text-gray-600">IDR {{ number_format($roomType->harga, 0, ',', '.') }}/malam</p>
            </div>
            <!-- Jumlah Kamar -->
            <div>
            <h3 class="text-base md:text-sm font-semibold text-gray-700">Jumlah Kamar</h3>
            <p class="text-gray-600">{{ $jumlahKamar }} Kamar</p>
            </div>
        </div>

        <!-- Fasilitas -->
        <div class="mt-6">
            <h3 class="text-base md:text-sm font-semibold text-gray-700 mb-2">Fasilitas</h3>
            <ul class="list-disc pl-6 text-gray-600">
                @foreach ($fasilitasArray as $fasilitas)
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-rose-800"></i>
                        <span>{{ trim($fasilitas) }}</span> <!-- trim untuk menghapus spasi di awal/akhir -->
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection
