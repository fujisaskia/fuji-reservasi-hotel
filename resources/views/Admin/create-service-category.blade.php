@extends('layouts/admin')

@section('title', 'Tambah Kategori Layanan | Admin')

@section('content')
    <div class="max-w-xl mx-auto py-12 px-6 bg-white rounded-lg shadow-md text-xs">
        <h2 class="text-lg font-bold text-center pb-2 border-b mb-6">Tambah Kategori Layanan</h2>

        <!-- Menampilkan pesan sukses jika ada -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('service-categories.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-yellow-100 @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-yellow-100 @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Tambah Kategori
                </button>
            </div>
        </form>
    </div>
@endsection
