<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Room Management | Admin')

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

<div class="container mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
        <!-- Header -->
        <h2 class="text-lg md:text-2xl font-bold text-center mb-4">Manajement Kamar Hotel</h2>
        <div class="flex justify-center items-center mb-6 text-xs">
            <div class="w-full max-w-md">
                <form class="flex items-center">
                    <input type="search" class="w-full p-3 lg:p-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="Cari kamar ...">
                    <button class="bg-rose-500 text-white py-4 lg:py-3 px-4 rounded-r-lg hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Tambah Kamar Button -->
        <a href="/add-room/admin">
            <button class="flex space-x-2 text-white bg-green-600 hover:bg-green-700 focus:bg-green-600 px-4 py-3 lg:py-2 rounded-lg mb-3 ">
                <i class="fa-solid fa-plus"></i>
                <p>Tambah Kamar</p>
            </button>
        </a>

        <!-- Tabel Daftar Kamar -->
        <div class="overflow-x-auto">
            <!-- resources/views/rooms/index.blade.php -->
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-rose-100">
                    <tr class="text-left">
                        <th class="px-4 py-2 border-b text-center">No</th>
                        <th class="px-2 py-2 border-b text-center">No. Kamar</th>
                        <th class="px-4 py-2 border-b">Tipe Kamar</th>                        
                        <th class="px-4 py-2 border-b text-center">Status Kamar</th>
                        <th class="px-4 py-2 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-left">
                    @foreach($rooms as $room)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border-b text-center">{{ $loop->iteration }}</td>
                        <td class="px-2 py-2 border-b text-center">{{ $room->room_number }}</td>
                        <td class="px-4 py-2 border-b">{{ $room->roomType->tipe_kamar ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border-b text-center">
                            <span class="py-1 px-3 {{ $room->room_status == 'tersedia' ? 'bg-green-600' : 'bg-red-600' }} text-white rounded-3xl">
                                {{ ucfirst($room->room_status) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 border-b text-center">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('rooms.edit', $room->id) }}" class="lg:flex space-x-2 items-center justify-center bg-orange-500 hover:bg-orange-600 text-white py-1 px-2 rounded-md shadow-lg hover:shadow-none">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="delete-room-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="lg:flex space-x-2 items-center justify-center bg-red-500 hover:bg-red-600 text-white p-2 rounded-md shadow-lg hover:shadow-none">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    
<script>

    //sweetalert untuk konfirmasi hapus
    document.addEventListener('DOMContentLoaded', function () {
        // Pilih semua form dengan kelas `delete-room-type-form`
        const deleteForms = document.querySelectorAll('.delete-room-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Cegah submit default

                // Tampilkan SweetAlert konfirmasi
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tipe kamar ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika dikonfirmasi, submit formulir
                        form.submit();
                    }
                });
            });
        });
    });
</script>


@endsection