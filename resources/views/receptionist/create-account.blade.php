@extends('layouts/receptionist')

@section('title', 'Buat Akun Tamu')

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

    {{-- form tambah user --}}
<div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-md text-sm md:text-xs">
    <h2 class="text-lg pb-3 border-b font-semibold text-gray-800 mb-4 text-center">Tambah  Akun Tamu</h2>
    
    <!-- Form Tambah User -->
    <form action="{{ route('create-guest-account') }}" method="POST">
        @csrf <!-- Token CSRF wajib -->
        
        <div class="flex space-x-2 mb-4">
            <!-- Title -->
            <div class="">
                <label for="title" class="block text-gray-700 mb-2">Title</label>
                <select id="title" name="title" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300">
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                </select>
            </div>
    
            <!-- Nama Lengkap -->
            <div class="flex-1">
                <label for="full_name" class="block text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nama lengkap" required>
                @error('full_name')
                    <p class="text-red-500  mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan email" required>
            @error('email')
                <p class="text-red-500  mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nomor Telepon -->
        <div class="mb-4">
            <label for="phone_number" class="block text-gray-700 mb-2">Nomor Telepon</label>
            <input type="text" id="phone_number" name="phone_number" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nomor telepon" required>
            @error('phone_number')
                <p class="text-red-500  mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-2 mb-4">
            <!-- Jenis Identitas -->
            <div class="">
                <label for="identification_type" class="block text-gray-700 mb-2">Jenis Identitas</label>
                <select id="identification_type" name="identification_type" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300">
                    <option value="KTP">KTP</option>
                    <option value="Passport">Passport</option>
                </select>
            </div>
    
            <!-- Nomor Identitas -->
            <div class="flex-1">
                <label for="identification_number" class="block text-gray-700 mb-2">Nomor Identitas</label>
                <input type="text" id="identification_number" name="identification_number" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nomor identitas">
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end space-x-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah
            </button>
        </div>
    </form>
</div>


@endsection


{{-- <a href="" class="bg-orange-400 text-white px-4 py-2 rounded hover:bg-orange-500">
    Batal
</a> --}}