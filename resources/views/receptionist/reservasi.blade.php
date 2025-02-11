<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'Reservasi | receptionist')

@section('content')

@if(session('sweetalert'))
<script>
    Swal.fire({
        icon: '{{ session('sweetalert.type') }}',
        title: '{{ session('sweetalert.message') }}',
        customClass: {
                title: 'text-base' // Tambahkan kelas kustom
        },
    });
</script>
@endif

<div class="container mx-auto bg-white py-8 px-6 shadow-md border border-gray-200 rounded-lg text-sm md:text-xs">
    
    @if(session('status'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 border border-green-200 rounded">
            {!! html_entity_decode(session('status')) !!}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-700 border border-red-200 rounded">
            {!! html_entity_decode(session('error')) !!}
        </div>
    @endif
    <h2 class="text-2xl font-bold text-center mb-4">Reservasi Kamar Hotel</h2>
    
    <!-- Filter dan Search -->
    <div class="flex justify-center items-center mb-8">
        <form action="{{ route('reservasi.index') }}" method="GET" class="flex items-center space-x-2">
            <select name="status" class="border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-yellow-200 text-xs">
                <option value="">Status</option>
                @foreach(['Pending', 'Confirmed', 'Checked-In', 'Checked-Out', 'Cancelled'] as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
                @endforeach
            </select>
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama / invoice" 
                value="{{ request('search') }}" 
                class="border border-gray-300 rounded p-2 w-full md:w-64 focus:outline-none focus:ring focus:ring-yellow-200"
            >
            <button type="submit" class="bg-rose-700 hover:bg-rose-800 focus:scale-95 text-white px-4 py-2 rounded-full text-sm duration-300"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>


    <!-- Tambah Reservasi Button -->
    <div class="flex justify-end">
        <a href="/create-reservation">
            <button class="flex space-x-2 text-white items-center bg-green-600 hover:bg-green-700 focus:bg-green-600 p-3 lg:py-2 rounded-lg mb-3 ">
                <i class="fa-solid fa-plus"></i>
                <p>Buat Reservasi</p>
            </button>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-rose-100 border-b border-gray-300">
                    <th class="p-3 lg:p-2 text-center font-semibold text-gray-600">No</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Nama Tamu</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tipe Kamar</th>                    
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Harga (IDR)</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tgl. Reservasi</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tgl. check-In</th>
                    {{-- <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Status Bayar</th> --}}
                    <th class="p-3 lg:p-2 text-center font-semibold text-gray-600">Status Reservasi</th>
                    <th class="p-3 lg:p-2 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $index => $reservation)
                <tr class="hover:bg-gray-100 border-b border-gray-300">
                    <td class="p-3 lg:p-2 text-gray-600 text-center">{{ $index + 1 }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ $reservation->user->full_name }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ $reservation->roomType->tipe_kamar }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">IDR  {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                    <td class="p-3 lg:p-2 text-gray-500">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                    <td class="p-2 text-[11px] text-white text-center align-middle">
                        <span class="py-1 px-3 text-[10px] rounded-full italic flex items-center justify-center
                            @if ($reservation->reservation_status === 'Pending') 
                                bg-yellow-50 text-yellow-700 border border-yellow-400
                            @elseif ($reservation->reservation_status === 'Confirmed') 
                                bg-green-50 text-green-700 border border-green-400
                            @elseif ($reservation->reservation_status === 'Checked-In') 
                                bg-blue-50 text-blue-700 border border-blue-400
                            @elseif ($reservation->reservation_status === 'Checked-Out') 
                                bg-rose-50 text-rose-700 border border-rose-400
                            @elseif ($reservation->reservation_status === 'Cancelled') 
                                bg-red-50 text-red-700 border border-red-400
                            @endif">
                            {{ $reservation->reservation_status }}
                        </span>
                    </td>                                       
                    <td class="flex space-x-2 p-3 lg:p-2 justify-center">
                        <div class="flex space-x-2">
                            
                            <div x-data="{ openDetailReservasi: false }" class="">
                                <button  @click="openDetailReservasi = true">
                                    @include('components.button-read')
                                </button>                            
                                @include('components.detail-reservasi')
                            </div>
                            {{-- Tombol konfirmasi reservasi --}}
                            <form action="{{ route('reservation.confirm', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center text-white p-2 rounded-md shadow-lg hover:shadow-none
                                        @if(in_array($reservation->reservation_status, ['Confirmed', 'Cancelled', 'Checked-In', 'Checked-Out']))
                                            bg-gray-400 cursor-not-allowed
                                        @else
                                            bg-green-500 hover:bg-green-600 focus:scale-95 duration-300
                                        @endif"
                                        @if(in_array($reservation->reservation_status, ['Confirmed', 'Cancelled', 'Checked-In', 'Checked-Out']))
                                            disabled
                                        @endif
                                        title="{{ $reservation->reservation_status === 'Pending' ? 'Konfirmasi Reservasi' : 'Reservasi telah di ' . strtolower($reservation->reservation_status) }}">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        
        {{-- <div class="flex items-center justify-center mt-4 text-[10px]">
            <nav class="flex space-x-2" aria-label="Pagination">
                <!-- Previous Button -->
                <a href="#" class="px-3 py-2 border rounded-l bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Previous
                </a>
        
                <a href="#" class="px-3 py-2 border bg-rose-800 text-white hover:bg-gray-300 rounded-full">1</a>
                <a href="#" class="px-3 py-2 border bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-full">2</a>
                <a href="#" class="px-3 py-2 border bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-full">3</a>
                <a href="#" class="px-3 py-2 border bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-full">4</a>        
                <a href="#" class="px-3 py-2 border bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-full">5</a>
        
                <!-- Next Button -->
                <a href="#" class="px-3 py-2 border rounded-r bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Next
                </a>
            </nav>
        </div> --}}

        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
        
    </div>
</div>




@endsection
    
