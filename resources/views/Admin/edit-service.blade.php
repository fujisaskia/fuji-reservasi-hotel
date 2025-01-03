@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
    <div class="max-w-xl mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
        <h2 class="text-lg pb-2 border-b font-bold text-center mb-4">Edit Service</h2>

        <!-- Menampilkan pesan sukses jika ada -->
        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <form action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Method spoofing untuk HTTP PUT -->

            <div class="mb-4">
                <label for="name" class="block text-gray-700">Service Name</label>
                <input type="text" name="name" id="name" class="w-full p-3 border border-gray-300 rounded-lg" value="{{ old('name', $service->name) }}" required>
                @error('name')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="service_category_id" class="block text-gray-700">Service Category</label>
                <select name="service_category_id" id="service_category_id" class="w-full p-3 border border-gray-300 rounded-lg" required>
                    <option value="">Select a Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('service_category_id', $service->service_category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('service_category_id')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700">Price</label>
                <input type="number" name="price" id="price" class="w-full p-3 border border-gray-300 rounded-lg" value="{{ old('price', $service->price) }}" required>
                @error('price')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="w-1/3 bg-yellow-600 text-white py-3 rounded-lg hover:bg-yellow-700">
                    Update Service
                </button>
            </div>
        </form>
    </div>
@endsection
