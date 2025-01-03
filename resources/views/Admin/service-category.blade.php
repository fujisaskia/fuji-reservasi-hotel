@extends('layouts/admin')

@section('title', 'Service Category | Admin')

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
        <h2 class="text-lg md:text-2xl font-bold text-center mb-4">Kategori Layanan</h2>

        <!-- Tambah Kamar Button -->
        <a href="{{ route('service-categories.create') }}">
            <button class="flex space-x-2 text-white bg-green-600 hover:bg-green-700 focus:bg-green-600 px-4 py-3 lg:py-2 rounded-lg mb-3">
                <i class="fa-solid fa-plus"></i>
                <p>Tambah Kategory</p>
            </button>
        </a>

        <!-- Tabel Daftar Kamar -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-rose-100">
                    <tr class="text-left">
                        <th class="px-4 py-2 border-b text-center">No</th>
                        <th class="px-4 py-2 border-b">Kategori</th>
                        <th class="px-4 py-2 border-b">Deskripsi</th>
                        <th class="px-4 py-2 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-left">

                    <!-- Looping data kategori -->
                    @foreach($categories as $index => $category)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border-b">{{ $category->name }}</td>
                            <td class="px-4 py-2 border-b">{{ $category->description }}</td>

                            <td class="px-2 py-2 border-b text-center">
                                <div class="flex space-x-2 justify-center">
                                    <a href="{{ route('service-categories.edit', $category->id) }}">
                                        @include('components.button-edit')
                                    </a>

                                    <form action="{{ route('service-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        @include('components.button-delete')
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
