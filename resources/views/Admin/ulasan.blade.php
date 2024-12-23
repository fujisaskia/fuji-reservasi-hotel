@extends('layouts/admin')

@section('title', 'Ulasan Kamar | Admin')

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
<div class="max-w-5xl bg-white mx-auto px-8 py-8 rounded-md shadow-md text-xs">

    <h1 class="text-center text-2xl font-bold mb-6">Daftar Ulasan</h1>
    
    <!-- Tabel Ulasan -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-200 bg-white">
            <thead class="bg-rose-100 uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Nama User</th>
                    <th class="px-4 py-2 text-center">Rating</th>
                    <th class="px-4 py-2 text-left">Komentar</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ulasans as $index => $ulasan)
                <tr class="{{ $ulasan->is_visible ? 'hover:bg-gray-50' : 'bg-gray-200' }} border-b">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $ulasan->user->full_name }}</td>
                    <td class="px-4 py-2 text-center text-yellow-500 text-lg">
                        {{ $ulasan->formattedRating() }}
                    </td>
                    <td class="px-4 py-2">{{ $ulasan->comment ?? 'Tidak ada komentar' }}</td>
                    <td class="px-4 py-2 text-center">
                        <form action="{{ route('ulasans.toggleVisibility', $ulasan->id) }}" method="POST">
                            @csrf
                            @method('POST') <!-- Menggunakan POST untuk toggle -->
                            <button type="submit" 
                                    class="{{ $ulasan->is_visible ? 'text-white bg-blue-600 p-1 rounded-md hover:bg-blue-700' : 'text-white bg-rose-600 p-1 rounded-md hover:bg-rose-700' }} text-[11px] focus:scale-95 duration-300">
                                {{ $ulasan->is_visible ? 'Sembunyikan' : 'Tampilkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pesan jika tidak ada ulasan -->
    @if($ulasans->isEmpty())
        <p class="mt-4 text-gray-500">Belum ada ulasan untuk ditampilkan.</p>
    @endif
</div>
@endsection
