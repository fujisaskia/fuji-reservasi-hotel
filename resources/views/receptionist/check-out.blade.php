<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'check-in | receptionist')

@section('content')

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: true,
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        text: '{{ $errors->first() }}',
        showConfirmButton: true,
    });
</script>
@endif

<div class="container mx-auto py-8 px-2">  
    <h4 class="bg-white sticky top-24 py-3 px-4 rounded-lg shadow-md md:w-1/2 mb-8 lg:text-sm">
        Pilih kamar untuk check-out
    </h4>
             
        <!-- resources/views/occupied-rooms.blade.php -->
        <div class="flex items-center justify-center md:justify-start text-xs">
            {{-- <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($occupiedRooms as $room)
                    <a href="{{ route('check-out.show', $room->id) }}" class="block hover:-translate-y-1 duration-300">
                        <div class="text-center justify-center items-center py-6 px-12 bg-gradient-to-l from-rose-800 to-rose-500 text-white hover:shadow-xl rounded-md duration-300">
                            <div class="flex space-x-3 items-center mx-auto justify-center">
                                <i class="fa-solid fa-bed fa-3x"></i>
                                <div class="lg:text-start uppercase">
                                    <h4 class="text-3xl font-semibold">{{ $room->room_number }}</h4>
                                    <p class="uppercase">{{ optional($room->roomType)->tipe_kamar }}</p>
                                </div>
                            </div>  
                        </div>                
                    </a>
                @endforeach
            </div> --}}
            <div class="container lg:max-w-5xl mx-auto p-4 bg-white shadow-lg rounded-lg">
                <!-- Input dan Button Search-->
                <div class="flex gap-2 mb-4">
                    <form method="GET" action="{{ route('check-out.index') }}" class="flex">
                        <input type="search" name="search" placeholder="Cari No. Kamar atau Nama Tamu" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Cari</button>
                    </form>
                </div>
                
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2 border">No. Kamar</th>
                                    <th class="p-2 border">Tamu</th>
                                    <th class="p-2 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($occupiedRooms as $room)
                                    @foreach($room->reservation as $reservation)
                                        <tr class="hover:bg-gray-100">
                                            <td class="p-2 border text-center">{{ $room->room_number }}</td>
                                            <td class="p-2 border">{{ $reservation->user->full_name }}</td>
                                            <td class="p-2 border text-center flex items-center justify-evenly">
                                                <a href="{{ route('invoice.show', $reservation->id) }}">
                                                    <button type="button" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600">
                                                        Lihat Invoice
                                                    </button>
                                                </a>
                                                <label class="flex items-center justify-center">
                                                    <input type="checkbox" name="selected_rooms[]" value="{{ $room->id }}" class="room-checkbox">
                                                    <input type="hidden" name="reservation_ids[{{ $room->id }}]" value="{{ $reservation->id }}">
                                                    <span class="ml-2">Pilih Kamar</span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center border border-gray-300 px-4 py-2">Tidak ada kamar yang sedang ditempati</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                
                    <div class="flex justify-end mt-12 text-center">
                        <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Check-out</button>
                    </div>
                </form>                
                
            </div>
        </div>

</div>    

@endsection
    
