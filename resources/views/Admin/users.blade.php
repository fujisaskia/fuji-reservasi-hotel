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

    <a href="/users/create">
        <button class="flex space-x-2 text-white bg-green-500 hover:bg-green-600 focus:bg-green-500 text-sm lg:text-xs px-4 py-3 lg:py-2 rounded-lg my-6">
            <i class="fa-solid fa-user-plus"></i>
            <p>Tambah User</p>
        </button>
    </a>
    
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
                                <a href="" class="flex space-x-2 items-center justify-center bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded-md shadow-lg hover:shadow-none">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('users.edit', $user->id) }}" class="lg:flex space-x-2 items-center justify-center bg-orange-500 hover:bg-orange-600 text-white py-1 px-2 rounded-md shadow-lg hover:shadow-none">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </a>

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
    
