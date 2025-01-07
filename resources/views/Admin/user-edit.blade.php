@extends('layouts/admin')

@section('title', 'Edit User')

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

{{-- Form Edit User --}}
<div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-md text-sm md:text-xs">
    <h2 class="text-lg pb-3 border-b font-semibold text-gray-800 mb-4 text-center">Edit User</h2>
    
    <!-- Form Edit User -->
    <form action="{{ route('users.update', $user->id) }}" method="POST" class="">
        @csrf
        @method('PUT') <!-- Menggunakan PUT untuk update -->

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 mb-2">Title</label>
            <select id="title" name="title" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                <option value="Mr" {{ $user->title == 'Mr' ? 'selected' : '' }}>Mr</option>
                <option value="Mrs" {{ $user->title == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                <option value="Ms" {{ $user->title == 'Ms' ? 'selected' : '' }}>Ms</option>
            </select>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-4">
            <label for="full_name" class="block text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" id="full_name" name="full_name" value="{{ $user->full_name }}" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nama lengkap" required>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan email" required>
        </div>

        <!-- Role -->
        <div class="mb-4">
            <label for="role" class="block text-gray-700 mb-2">Role</label>
            <select id="role" name="role" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Tamu</option>
                <option value="receptionist" {{ $user->role == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <!-- Nomor Telepon -->
        <div class="mb-4">
            <label for="phone_number" class="block text-gray-700 mb-2">Nomor Telepon</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nomor telepon" required>
        </div>

        <!-- Kewarganegaraan -->
        <div class="mb-4">
            <label for="nationality" class="block text-gray-700 mb-2">Kewarganegaraan</label>
            <input type="text" id="nationality" name="nationality" value="{{ $user->nationality }}" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Contoh: Indonesia" required>
        </div>

        <!-- Jenis Identitas -->
        <div class="mb-4">
            <label for="identification_type" class="block text-gray-700 mb-2">Jenis Identitas</label>
            <select id="identification_type" name="identification_type" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                <option value="KTP" {{ $user->identification_type == 'KTP' ? 'selected' : '' }}>KTP</option>
                <option value="Passport" {{ $user->identification_type == 'Passport' ? 'selected' : '' }}>Passport</option>
            </select>
        </div>

        <!-- Nomor Identitas -->
        <div class="mb-4">
            <label for="identification_number" class="block text-gray-700 mb-2">Nomor Identitas</label>
            <input type="text" id="identification_number" name="identification_number" value="{{ $user->identification_number }}" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan nomor identitas" required>
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-gray-700 mb-2">Password (Kosongkan jika tidak ingin diubah)</label>
            <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Masukkan password baru">
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update User
            </button>
            <a href="{{ route('users.index') }}" class="bg-orange-400 text-white px-4 py-2 rounded hover:bg-orange-500">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
