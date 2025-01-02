<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'Rooms | receptionist')

@section('content')

@if(session('sweetalert'))
<script>
    Swal.fire({
        icon: '{{ session('sweetalert.type') }}',
        title: '{{ session('sweetalert.message') }}',
        customClass: {
                title: 'swal-small-text' // Tambahkan kelas kustom
        },
    });
</script>
@endif

<div class="max-w-5xl mx-auto bg-white py-8 px-6 rounded-lg">
    <h2 class="text-2xl font-bold text-center mb-4">Kamar Hotel</h2>
    <div class="flex space-x-2 justify-center items-center mb-6 text-xs">
        <div class="">
            <form method="GET" action="{{ route('receptionist.rooms.index') }}" class="flex space-x-2 items-center">
                <select name="status" id="status" class="p-3 lg:p-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-200">
                    <option value="">Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="perawatan" {{ request('status') == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                </select>
                <div class="w-full max-w-md">
                    <div class="flex items-center">
                        <input 
                            type="search" 
                            name="search" 
                            value="{{ request('search') }}" 
                            class="w-full p-3 lg:p-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-200" 
                            placeholder="Cari No. Kamar">
                        <button class="bg-rose-500 text-white py-4 lg:py-3 px-4 rounded-r-lg hover:bg-rose-600 focus:outline-none focus:scale-90 duration-300">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-sm md:text-xs text-left">
            <thead>
                <tr class="bg-rose-100 text-gray-800">
                    <th class="py-3 px-4 text-center">No. Kamar</th>
                    <th class="py-3 px-4">Tipe Kamar</th>
                    <th class="py-3 px-4 lg:px-8">Status Kamar</th>
                    <th class="py-3 px-4 lg:px-8">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                <tr class="hover:bg-gray-100 border-b">
                    <td class="py-3 px-4 text-center">{{ $room->room_number }}</td>
                    <td class="py-3 px-4">{{ $room->roomType->tipe_kamar }}</td>
                    <td class="py-3 px-4 lg:px-8 text-[11px] text-black">
                        @if ($room->room_status == 'tersedia')
                            <p class="w-full md:w-1/2 py-1.5 px-2 border border-green-700 bg-green-50 rounded-full text-center">Tersedia</p>
                        @elseif ($room->room_status == 'terisi') 
                            <p class="w-full md:w-1/2 py-1.5 px-2 border border-rose-700 bg-rose-50 rounded-full text-center">Terisi</p>
                        @elseif ($room->room_status == 'perawatan')
                            <p class="w-full md:w-1/2 py-1.5 px-2 border border-yellow-700 bg-yellow-50 rounded-full text-center">Perawatan</p>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex space-x-2 justify-center"> 
                            <a href="{{ route('receptionist.rooms.edit', $room->id) }}" class="flex space-x-2 items-center justify-center bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded-md shadow-lg hover:shadow-none">
                                <i class="fa-solid fa-pen-nib"></i>
                                <p class="">Ubah Status</p>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 px-4 text-center text-gray-500">Tidak ada data kamar yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
            
        </table>

    </div>
    <div class="mt-4">
        {{ $rooms->links() }}
    </div>
</div>



@endsection
    
