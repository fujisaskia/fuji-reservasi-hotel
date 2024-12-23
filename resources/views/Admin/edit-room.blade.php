<!-- resources/views/rooms/edit.blade.php -->
@extends('layouts.admin')

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
    <h2 class="text-xl font-semibold mb-4">Edit Data Kamar</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4"> 
            <div class="flex space-x-2 w-full">
                <!-- Nomor Kamar -->
                <div class="flex-1">
                    <label for="room_number" class="block text-gray-700 mb-2">Nomor Kamar</label>
                    <input type="number" id="room_number" name="room_number" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" value="{{ $room->room_number }}" required>
                </div>

                <!-- Tipe Kamar dari table room_types -->
                <div class="flex-1">
                    <label for="room_type_id" class="block text-gray-700 mb-2">Tipe Kamar</label>
                    <select id="room_type_id" name="room_type_id" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" {{ $type->id == $room->room_type_id ? 'selected' : '' }}>
                                {{ $type->tipe_kamar }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Status Kamar -->
            <div class="flex-1">
                <label for="room_status" class="block text-gray-700 mb-2">Status Kamar</label>
                <select id="room_status" name="room_status" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                    <option value="tersedia" {{ $room->room_status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="perawatan" {{ $room->room_status == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                    <option value="terisi" {{ $room->room_status == 'terisi' ? 'selected' : '' }}>Terisi</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-end mt-6">
            <button type="submit" class="flex space-x-2 text-white bg-blue-600 hover:bg-blue-700 focus:bg-blue-600 p-2 rounded-lg">
                <i class="fa-solid fa-save"></i>
                <p>Simpan</p>
            </button>
        </div>
    </form>
</div>
@endsection
