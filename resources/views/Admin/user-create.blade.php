@extends('layouts/admin')

@section('title', 'Tambah User')

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
    <h2 class="text-lg pb-3 border-b font-semibold text-gray-800 mb-4 text-center">Tambah User</h2>
    
    <!-- Form Tambah User -->
    <form action="{{ route('users.store') }}" method="POST" class="">
        @csrf <!-- Token CSRF wajib -->
        
        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 mb-2">Title</label>
            <select id="title" name="title" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300">
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
            </select>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-4">
            <label for="full_name" class="block text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" id="full_name" name="full_name" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nama lengkap" required>
            @error('full_name')
                <p class="text-red-500  mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan email" required>
            @error('email')
                <p class="text-red-500  mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role -->
        <div class="mb-4">
            <label for="role" class="block text-gray-700 mb-2">Role</label>
            <select id="role" name="role" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                <option value="user">Tamu</option>
                <option value="receptionist">receptionist</option>
                <option value="admin">Admin</option>
            </select>
            @error('role')
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

        <!-- Kewarganegaraan -->
        <div class="mb-4">
            <label for="nationality" class="block text-gray-700 mb-2">Kewarganegaraan</label>
            <input type="text" id="nationality" name="nationality" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Contoh: Indonesia">
        </div>

        <!-- Jenis Identitas -->
        <div class="mb-4">
            <label for="identification_type" class="block text-gray-700 mb-2">Jenis Identitas</label>
            <select id="identification_type" name="identification_type" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300">
                <option value="KTP">KTP</option>
                <option value="Passport">Passport</option>
            </select>
        </div>

        <!-- Nomor Identitas -->
        <div class="mb-4">
            <label for="identification_number" class="block text-gray-700 mb-2">Nomor Identitas</label>
            <input type="text" id="identification_number" name="identification_number" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nomor identitas">
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-gray-700 mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan password" required>
            @error('password')
                <p class="text-red-500  mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end space-x-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah User
            </button>
            <a href="{{ route('users.index') }}" class="bg-orange-400 text-white px-4 py-2 rounded hover:bg-orange-500">
                Batal
            </a>
        </div>
    </form>
</div>


@endsection