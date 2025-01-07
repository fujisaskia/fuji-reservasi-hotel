@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-2 text-sm md:text-xs">
    <h1 class="text-2xl font-bold mb-4 text-center">Pengaturan Hotel</h1>

    @if(session('success'))
        <div class="p-4 mb-4 text-green-800 bg-green-200 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('hotel-settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white border shadow-lg p-4">
        @csrf

        <!-- Nama Hotel -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-gray-700">Nama Hotel</label>
            <input type="text" id="name" name="name" value="{{ old('name', $hotelSetting->name ?? '') }}" 
                class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <!-- Deskripsi Hotel -->
        <div class="mb-4">
            <label for="description" class="block font-medium text-gray-700">Deskripsi Hotel</label>
            <textarea id="description" name="description" rows="4" 
                class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">{{ old('description', $hotelSetting->description ?? '') }}</textarea>
        </div>

        <!-- Alamat Hotel -->
        <div class="mb-4">
            <label for="address" class="block font-medium text-gray-700">Alamat</label>
            <input type="text" id="address" name="address" value="{{ old('address', $hotelSetting->address ?? '') }}" 
                class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <!-- Email Hotel -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $hotelSetting->email ?? '') }}" 
                class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <!-- Nomor Telepon Hotel -->
        <div class="mb-4">
            <label for="phone" class="block font-medium text-gray-700">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $hotelSetting->phone ?? '') }}" 
                class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <!-- Logo Hotel -->
        <div class="mb-4">
            <label for="logo" class="block font-medium text-gray-700">Logo Hotel</label>
            <input type="file" id="logo" name="logo" accept="image/*" 
                class="mt-1 block w-full p-2 border rounded">
            @if ($hotelSetting && $hotelSetting->logo_path)
                <img src="{{ asset('storage/' . $hotelSetting->logo_path) }}" alt="Logo Hotel" class="mt-2 h-20">
            @endif
        </div>

        <!-- Media Sosial -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="">
                <label for="instagram" class="block font-medium text-gray-700">Instagram</label>
                <input type="text" id="instagram" name="instagram" value="{{ old('instagram', $hotelSetting->instagram ?? '') }}" 
                    class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
            </div>
    
            <div class="">
                <label for="facebook" class="block font-medium text-gray-700">Facebook</label>
                <input type="text" id="facebook" name="facebook" value="{{ old('facebook', $hotelSetting->facebook ?? '') }}" 
                    class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
            </div>
    
            <div class="">
                <label for="tiktok" class="block font-medium text-gray-700">TikTok</label>
                <input type="text" id="tiktok" name="tiktok" value="{{ old('tiktok', $hotelSetting->tiktok ?? '') }}" 
                    class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
            </div>
    
            <div class="">
                <label for="whatsapp" class="block font-medium text-gray-700">WhatsApp</label>
                <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $hotelSetting->whatsapp ?? '') }}" 
                    class="mt-1 block w-full p-2 border rounded focus:ring focus:ring-indigo-300">
            </div>
        </div>


        <!-- Foto Hotel -->
        <div class="mb-4">
            <label for="photos" class="block font-medium text-gray-700">Foto Hotel (maksimum 3)</label>
            <input type="file" id="photos" name="photos[]" multiple accept="image/*" 
                class="mt-1 block w-full p-2 border rounded">

                @if ($hotelSetting && $hotelSetting->photos->count() > 0)
                    <div class="flex flex-col md:flex-row space-y-4 md:space-x-4 mt-2">
                        @foreach ($hotelSetting->photos as $photo)
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto Hotel" class="w-40 md:w-auto h-20">
                        @endforeach
                    </div>
                @endif
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end mt-8">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
