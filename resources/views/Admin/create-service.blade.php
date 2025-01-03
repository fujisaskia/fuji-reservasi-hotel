@extends('layouts.admin')

@section('title', 'Create Service | Admin')

@section('content')
    <div class="max-w-xl mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-center pb-2 border-b mb-4">Tambah Layanan Baru</h2>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Nama Layanan</label>
                <input type="text" id="name" name="name" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring focus:ring-yellow-100" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex space-x-4 justify-between mb-4">
                <div class="w-full">
                    <label for="service_category_id" class="block font-medium text-gray-700">Kategori Layanan</label>
                    <select id="service_category_id" name="service_category_id" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring focus:ring-yellow-100" required>
                        <option class="p-2" value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option class="p-2" value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_category_id')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="price" class="block font-medium text-gray-700">Harga</label>
                    <input type="number" id="price" name="price" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring focus:ring-yellow-100" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 text-white py-3 px-6 rounded-lg hover:bg-green-600 focus:outline-none">
                    Tambah Layanan
                </button>
            </div>
        </form>
    </div>
@endsection
