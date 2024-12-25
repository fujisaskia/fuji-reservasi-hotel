@extends($layout)

@section('title', 'Profile ' . $user->full_name)

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

<div class="bg-white shadow-md rounded-lg p-6 max-w-3xl w-full mx-auto text-xs">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-700 pb-2 border-b border-gray-300">Profile</h1>
    <form action="{{ route('profile.update', $user->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf <!-- Tambahkan CSRF token untuk keamanan -->
        @method('PUT') <!-- Menambahkan metode PUT di sini -->
        
        <!-- Nama -->
        <div>
            <label class="block text-gray-600 font-medium mb-1">Nama</label>
            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" 
                   class="bg-gray-50 border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring focus:ring-yellow-100">
        </div>
        
        <!-- Email -->
        <div>
            <label class="block text-gray-600 font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                   class="bg-gray-50 border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring focus:ring-yellow-100">
        </div>
        
        <!-- No. HP -->
        <div>
            <label class="block text-gray-600 font-medium mb-1">No. HP</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                   class="bg-gray-50 border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring focus:ring-yellow-100">
        </div>
        
        <!-- Peran -->
        <div>
            <label class="block text-gray-600 font-medium mb-1">Peran</label>
            <p class="bg-gray-50 border border-gray-300 rounded-md p-3 cursor-not-allowed">{{ $user->role }}</p>
        </div>
        
        <!-- Tombol Simpan -->
        <div class="md:col-span-2 text-center flex justify-end mt-12">
            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600 focus:ring focus:ring-yellow-100">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
