<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Room Type Management | Admin')

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



    <div class="max-w-5xl  mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
        <!-- Header -->
        <h2 class="text-lg md:text-2xl font-bold text-center mb-4">Tipe Kamar Hotel</h2>
        {{-- <div class="flex justify-center items-center mb-6 text-xs">
            <div class="w-full max-w-md">
                <form class="flex items-center">
                    <input type="search" class="w-full p-3 lg:p-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="Cari kamar ...">
                    <button class="bg-rose-500 text-white py-4 lg:py-3 px-4 rounded-r-lg hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div> --}}
        <!-- Tambah Kamar Button -->
        <a href="{{ route('room-types.create') }}">
            <button class="flex space-x-2 text-white bg-green-600 hover:bg-green-700 focus:bg-green-600 px-4 py-3 lg:py-2 rounded-lg mb-3 ">
                <i class="fa-solid fa-plus"></i>
                <p>Tambah Tipe</p>
            </button>
        </a>

        <!-- Tabel Daftar Kamar -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-rose-100">
                    <tr class="text-left">
                        <th class="px-4 py-2 border-b text-center">No</th>
                        <th class="px-4 py-2 border-b">Tipe Kamar</th>
                        <th class="px-4 py-2 border-b">Kapasitas</th>
                        <th class="px-4 py-2 border-b">Harga</th>
                        <th class="px-4 py-2 border-b">Fasilitas</th>
                        <th class="px-4 py-2 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody  class="text-left">
                    @foreach($roomTypes as $roomType)
                    <!-- Contoh Data -->
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border-b text-center">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border-b">{{ $roomType->tipe_kamar }}</td>
                        <td class="px-4 py-2 border-b">{{ $roomType->kapasitas }} Orang</td>
                        <td class="px-4 py-2 border-b">IDR {{ number_format($roomType->harga, 0, ',', ',') }}</td>
                        <td class="px-4 py-2 border-b">{{ Str::limit($roomType->fasilitas, 30) }}</td>

                        <td class="px-2 py-2 border-b text-center">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('room-type.detail', $roomType->id) }}" class="">
                                    @include('components.button-read')
                                </a>
                                
                                <a href="{{ route('room-types.edit', $roomType) }}" class="">
                                    @include('components.button-edit')
                                </a>

                                <form id="delete-room-type-{{ $roomType->id }}" 
                                    action="{{ route('room-types.destroy', $roomType) }}" 
                                    method="POST" 
                                    class="delete-room-type-form">
                                  @csrf
                                  @method('DELETE')
                                  @include('components.button-delete')
                              </form>                              
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <!-- Tambahkan data kamar lain di sini -->
                </tbody>
            </table>
        </div>
    </div>

<script>

    //sweetalert untuk konfirmasi hapus
    document.addEventListener('DOMContentLoaded', function () {
        // Pilih semua form dengan kelas `delete-room-type-form`
        const deleteForms = document.querySelectorAll('.delete-room-type-form');
        
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