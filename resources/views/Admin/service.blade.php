@extends('layouts/admin')

@section('title', 'Service | Admin')

@section('content')

    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4 text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan pesan error jika ada -->
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4 text-xs">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-5xl mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
        <!-- Header -->
        <h2 class="text-lg md:text-2xl font-bold text-center mb-4">Layanan</h2>

        <!-- Tambah Kamar Button -->
        <a href="{{ route('services.create') }}">
            <button class="flex space-x-2 text-white bg-green-600 hover:bg-green-700 focus:bg-green-600 px-4 py-3 lg:py-2 rounded-lg mb-3">
                <i class="fa-solid fa-plus"></i>
                <p>Tambah Layanan</p>
            </button>
        </a>

        <!-- Tabel Daftar Kamar -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-rose-100">
                    <tr class="text-left">
                        <th class="px-4 py-2 border-b text-center">No</th>
                        <th class="px-4 py-2 border-b">Nama Layanan</th>
                        <th class="px-4 py-2 border-b">Kategori</th>
                        <th class="px-4 py-2 border-b">Harga</th>
                        <th class="px-4 py-2 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border-b">{{ $service->name }}</td>
                            <td class="px-4 py-2 border-b">{{ $service->serviceCategory->name }}</td>
                            <td class="px-4 py-2 border-b">Rp {{ number_format($service->price, 0, ',', ',') }}</td>
                            <td class="px-2 py-2 border-b text-center">
                                <div class="flex space-x-2 justify-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('services.edit', $service->id) }}">
                                        @include('components.button-edit') <!-- Tombol Edit -->
                                    </a>
                                
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        @include('components.button-delete') <!-- Tombol Delete -->
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
