<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Tamu | Admin')

@section('content')

<div class="container bg-white py-8 px-4 rounded-lg lg:mr-12 text-sm md:text-xs">
    <h2 class="text-2xl font-bold text-center mb-4">Tamu</h2>

    {{-- fitur pencarian --}}
    <div class="flex justify-center items-center mb-6">
        <div class="w-full max-w-md">
            <form action="{{ route('guest.admin') }}" method="GET" class="flex items-center">
                <input type="search" name="search" id="default-search" class="w-full p-3 lg:p-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-200" placeholder="Nama Tamu ..." value="{{ request('search') }}">
                <button type="submit" class="bg-rose-500 text-white py-4 lg:py-3 px-4 rounded-r-lg hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-rose-100">
                    <th class="py-3 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">No</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Nama Tamu</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Tipe Kamar</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Email</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">No. Telp / Handphone</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            @if($reservations->isEmpty())
                <p class="text-center text-gray-500">Tidak ada tamu yang ditemukan.</p>
            @else
            <tbody>
                @forelse ($reservations as $index => $reservation)
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $reservation->user->full_name }}</td>
                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $reservation->roomType->tipe_kamar }}</td>
                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $reservation->user->email }}</td>
                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600">{{ $reservation->user->phone_number }}</td>
                    <td class="py-2 px-3 border-b border-gray-200">
                        <div class="flex space-x-2 justify-center">
                            <a href="{{ route('lihat-layanan', $reservation->id) }}">
                                <span class="flex space-x-2 items-center justify-center bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md shadow-lg hover:shadow-none">
                                    <i class="fa-regular fa-eye"></i>
                                    <p class="text-xs">Lihat</p>

                                </span>
                            </a>                                                       
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-3 px-4 text-center text-gray-600">No data available</td>
                    </tr>
                @endforelse
 
                <!-- Tambahkan lebih banyak baris sesuai kebutuhan -->
            </tbody>
            @endif
        </table>
    </div>
</div>



@endsection
    
