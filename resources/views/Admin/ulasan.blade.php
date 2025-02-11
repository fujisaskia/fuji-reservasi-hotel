@extends('layouts/admin')

@section('title', 'Ulasan | Admin')

@section('content')

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
            showConfirmButton: true,
        });
    </script>
@endif
<div class="max-w-5xl bg-white mx-auto px-8 py-8 rounded-md shadow-md text-sm md:text-xs">

    <h1 class="text-center text-2xl font-bold mb-6">Daftar Ulasan</h1>

    
    <!-- Filter dan Search -->
    <div class="flex justify-center items-center mb-8 text-sm md:text-xs">
        <div class="w-full max-w-md">
            <form action="{{ route('ulasans.index') }}" method="GET" class="flex items-center space-x-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari ulasan / pengguna" 
                    value="{{ request('search') }}" 
                    class="border border-gray-300 rounded p-3 md:p-2 w-full focus:outline-none focus:ring focus:ring-yellow-200"
                >
                <button type="submit" class="bg-rose-700 hover:bg-rose-800 focus:scale-95 text-white px-4 py-2 rounded-full text-sm duration-300">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>

    
    <!-- Tabel Ulasan -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-200 bg-white">
            <thead class="bg-rose-100 uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Nama User</th>
                    <th class="px-4 py-2 text-center">Rating</th>
                    <th class="px-4 py-2 text-center">Komentar</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ulasans as $index => $ulasan)
                <tr class="{{ $ulasan->is_visible ? 'hover:bg-gray-50' : 'bg-gray-200' }} border-b">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $ulasan->user->full_name }}</td>
                    <td class="px-4 py-2 text-center text-yellow-500 text-lg">
                        {{ $ulasan->formattedRating() }}
                    </td>
                    <td class="px-4 py-2">{{ $ulasan->comment ?? 'Tidak ada komentar' }}</td>
                    {{-- status --}}
                    <td class="px-4 py-2 text-center">
                        <div class="py-1 px-2 rounded-md {{ $ulasan->is_visible ? 'bg-blue-100 text-blue-900' : 'bg-red-100 text-red-900' }}">
                            {{ $ulasan->is_visible ? 'Ditampilkan' : 'Disembunyikan' }}
                        </div>
                    </td>
                    {{-- aksi --}}
                    <td class="px-4 py-2 text-center">
                        <form action="{{ route('ulasans.toggleVisibility', $ulasan->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="{{ $ulasan->is_visible ? 'text-white bg-blue-600 p-2 rounded-md hover:bg-blue-700' : 'text-white bg-rose-600 p-2 rounded-md hover:bg-rose-700' }} text-[11px] shadow-lg hover:shadow-none focus:scale-95 duration-300"
                                    title="{{ $ulasan->is_visible ? 'Sembunyikan Ulasan' : 'Tampilkan Ulasan' }}">
                                {{-- Ikon tombol berdasarkan status --}}
                                @if($ulasan->is_visible)
                                    <i class="fa-regular fa-eye-slash"></i>
                                @else
                                    <i class="fa-regular fa-eye"></i>
                                @endif
                            </button>
                        </form>
                    </td>                    

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-500">Tidak ada ulasan ditemukan</td>
                </tr>
                
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $ulasans->links() }}
        </div>
    </div>
</div>
@endsection
