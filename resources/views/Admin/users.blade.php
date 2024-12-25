<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Users | Admin')

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

<div class="max-w-5xl mx-auto bg-white py-8 px-6 rounded-lg shadow-md border border-gray-200">
    <h2 class="text-2xl pb-6 font-bold text-center mb-4 border-b">Users</h2>

    <div class="flex items-center justify-between space-x-3 text-sm lg:text-xs">
        <a href="/users/create">
            <button class="flex space-x-2 text-white bg-green-500 hover:bg-green-600 focus:bg-green-500 px-4 py-3 lg:py-2 rounded-lg my-6">
                <i class="fa-solid fa-user-plus"></i>
                <p class="hidden md:flex">Tambah User</p>
            </button>
        </a>
        <form action="{{ route('users.index') }}" method="GET" class="flex gap-2 items-center">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama" 
                value="{{ request('search') }}" 
                class="border border-gray-300 rounded p-2 md:w-64 focus:outline-none focus:ring focus:ring-yellow-200"
            >
            <button 
                type="submit" 
                class="bg-rose-700 hover:bg-rose-800 focus:scale-95 text-white px-4 py-2 rounded-full text-sm duration-300"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        
    </div>
    
    <div x-data="{ showModal: false, userDetail: {} }">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-rose-100">
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm lg:text-xs font-semibold text-gray-600">No</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm lg:text-xs font-semibold text-gray-600">Nama</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm lg:text-xs font-semibold text-gray-600">Email</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm lg:text-xs font-semibold text-gray-600">Telepon</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm lg:text-xs font-semibold text-gray-600">Peran</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-center text-sm lg:text-xs font-semibold text-gray-600">Aksi</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)                    
                        <tr class="hover:bg-gray-100 group">
                            <td class="py-3 px-4 border-b border-gray-200 text-sm lg:text-xs text-gray-600">{{ $index +1 }}</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm lg:text-xs text-gray-600">{{ $user->full_name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm lg:text-xs text-gray-600">{{ $user->email }}</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm lg:text-xs text-gray-600">{{ $user->phone_number ?? 'nomor tidak tersedia' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm lg:text-xs text-gray-600 bg-slate-50 group-hover:bg-white">{{ $user->role }}</td>

                            <td class="py-2 px-3 border-b border-gray-200 text-sm lg:text-xs">
                                <div class="flex space-x-2 justify-center">
                                    <!-- Button Detail -->
                                    <button 
                                        @click="showModal = true; userDetail = {{ $user }}"
                                        class="flex space-x-2 items-center justify-center bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded-md shadow-lg hover:shadow-none">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    
                                    {{-- button edit --}}
                                    <a href="{{ route('users.edit', $user->id) }}" class="lg:flex space-x-2 items-center justify-center bg-orange-500 hover:bg-orange-600 text-white py-1 px-2 rounded-md shadow-lg hover:shadow-none">
                                        <i class="fa-solid fa-pen-nib"></i>
                                    </a>

                                    {{-- button hapus --}}
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $user->id }})"
                                            class="lg:flex space-x-2 items-center justify-center bg-red-500 hover:bg-red-600 text-white p-2 rounded-md shadow-lg hover:shadow-none">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>                                                                
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Tampilkan link pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

         <!-- Modal -->
         <div 
         x-show="showModal" 
         class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 px-4"
         x-cloak
     >
         <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg space-y-6 text-sm md:text-xs">
             <!-- Title -->
             <h2 class="text-2xl font-semibold text-gray-800 border-b pb-4">Detail Pengguna</h2>
     
             <!-- Grid Layout -->
             <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                 <!-- Title -->
                 <div>
                     <p class="font-medium text-gray-600">Title</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.title ?? 'Tidak diketahui'"></p>
                 </div>
                 <!-- Full Name -->
                 <div>
                     <p class="font-medium text-gray-600">Nama</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.full_name"></p>
                 </div>
                 <!-- Email -->
                 <div>
                     <p class="font-medium text-gray-600">Email</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.email"></p>
                 </div>
                 <!-- Phone Number -->
                 <div>
                     <p class="font-medium text-gray-600">Telepon</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.phone_number ?? 'Nomor tidak tersedia'"></p>
                 </div>
                 <!-- Nationality -->
                 <div>
                     <p class="font-medium text-gray-600">Nationality</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.nationality ?? 'Tidak diketahui'"></p>
                 </div>
                 <!-- Identification Type -->
                 <div>
                     <p class="font-medium text-gray-600">Identify Type</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.identification_type ?? 'Tidak diketahui'"></p>
                 </div>
                 <!-- Identification Number -->
                 <div>
                     <p class="font-medium text-gray-600">Identify No</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.identification_number ?? 'Tidak diketahui'"></p>
                 </div>
                 <!-- Role -->
                 <div>
                     <p class="font-medium text-gray-600">Peran</p>
                     <p class="text-gray-800 mt-1" x-text="userDetail.role"></p>
                 </div>
             </div>
     
             <!-- Close Button -->
             <div class="mt-6 text-right">
                 <button 
                     @click="showModal = false"
                     class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold"
                 >
                     Tutup
                 </button>
             </div>
         </div>
     </div>
     
</div>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda yakin ingin menghapus pengguna ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    }

</script>

@endsection
    
