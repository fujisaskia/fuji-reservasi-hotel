<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Tambah Kamar | Admin')

@section('content')

@if (session('sweetalert'))
    <script>
        Swal.fire({
            icon: '{{ session('sweetalert.type') }}', // 'success' atau 'error'
            title: '{{ session('sweetalert.message') }}',
            showConfirmButton: true,
            customClass: {
                title: 'swal-small-text' // Tambahkan kelas kustom
            },
        });
    </script>
@endif

<div class="max-w-lg mx-auto p-8 bg-white text-xs rounded-lg shadow-lg">
    <!-- Header -->
    <h1 class="text-lg md:text-xl font-semibold mb-6">Tambah Kamar Baru</h1>
    
    <!-- resources/views/rooms/create.blade.php -->
    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        <div class="space-y-4"> 
            <div class="flex space-x-2 w-full">
                <!-- Nomor Kamar -->
                <div class="flex-1">
                    <label for="room_number" class="block text-gray-700 mb-2">Nomor Kamar</label>
                    <input type="number" id="room_number" name="room_number" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                </div>

                <!-- Tipe Kamar dari table room_types -->
                <div class="flex-1">
                    <label for="room_type" class="block text-gray-700 mb-2">Tipe Kamar</label>
                    <select id="room_type" name="room_type" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->tipe_kamar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Status Kamar -->
            <div class="flex-1">
                <label for="room_status" class="block text-gray-700 mb-2">Status Kamar</label>
                <select id="room_status" name="room_status" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="perawatan">Perawatan</option>
                    <option value="terisi">Terisi</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <!-- Tombol Submit -->
            <button type="submit" class="flex space-x-2 text-white bg-green-600 hover:bg-green-700 focus:bg-green-600 p-2 rounded-lg">
                <i class="fa-solid fa-plus"></i>
                <p>Tambah Kamar</p>
            </button>
        </div>
    </form>

    
</div>



@endsection
    