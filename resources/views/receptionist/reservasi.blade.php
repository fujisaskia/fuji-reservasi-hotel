<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'Reservasi | receptionist')

@section('content')



<div class="container bg-white py-8 px-6 rounded-lg text-xs">
    
    @if(session('status'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 border border-green-200 rounded text-xs">
            {!! html_entity_decode(session('status')) !!}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-700 border border-red-200 rounded text-xs">
            {!! html_entity_decode(session('error')) !!}
        </div>
    @endif
    <h2 class="text-2xl font-bold text-center mb-4">Reservasi Kamar Hotel</h2>
    
    <!-- Filter dan Search -->
    <div class="flex justify-center items-center mb-8">
        <form action="{{ route('reservasi.index') }}" method="GET" class="flex items-center space-x-2">
            <select name="status" class="border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-yellow-200">
                <option value="">Filter Status</option>
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
                class="border border-gray-300 rounded p-2 md:w-64 focus:outline-none focus:ring focus:ring-yellow-200"
            >
            <button type="submit" class="bg-rose-700 hover:bg-rose-800 focus:scale-95 text-white px-4 py-2 rounded-full text-sm duration-300"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>


    <!-- Tambah Reservasi Button -->
    {{-- <div class="flex justify-end">
        <a href="/create-reservation">
            <button class="flex space-x-2 text-white text-xs items-center bg-green-600 hover:bg-green-700 focus:bg-green-600 p-3 lg:py-2 rounded-lg mb-3 ">
                <i class="fa-solid fa-plus"></i>
                <p>Buat Reservasi</p>
            </button>
        </a>
    </div> --}}

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-rose-100 border-b border-gray-300">
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">No</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Nama Tamu</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tipe Kamar</th>                    
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Harga (IDR)</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tgl. Reservasi</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tgl. check-In</th>
                    {{-- <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Status Bayar</th> --}}
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Status Reservasi</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $index => $reservation)
                <tr class="hover:bg-gray-100 border-b border-gray-300">
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600 text-center">{{ $index + 1 }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ $reservation->user->full_name }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ $reservation->roomType->tipe_kamar }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">IDR  {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-400">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                    {{-- <td class="p-2 text-[11px] font-semibold text-center">
{{$reservation->payment->payment_status
}} 
                    </td>                     --}}
                    <td class="p-2 text-[11px] text-white">
                        <form action="{{ route('reservation.confirm', $reservation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-1 w-full rounded-full justify-between italic shadow-xl hover:shadow-sm focus:shadow-none 
                                @if ($reservation->reservation_status === 'Pending') 
                                    bg-yellow-100 text-yellow-700 
                                @elseif ($reservation->reservation_status === 'Confirmed') 
                                    bg-green-100 text-green-600 
                                @elseif ($reservation->reservation_status === 'Checked-In') 
                                    bg-blue-100 text-blue-600 
                                @elseif ($reservation->reservation_status === 'Checked-Out') 
                                    bg-rose-100 text-rose-600 
                                @elseif ($reservation->reservation_status === 'Cancelled') 
                                    bg-red-100 text-red-700 
                                @endif">
                                {{ $reservation->reservation_status }}
                            </button>
                        </form>
                    </td>                    
                    <td class="flex space-x-2 p-3 lg:p-2  text-sm lg:text-xs">
                        <div class="flex space-x-2 justify-center">
                            
                            <div x-data="{ openDetailReservasi: false }" class="">
                                <button  @click="openDetailReservasi = true">
                                    @include('components.button-read')
                                </button>                            
                                @include('components.detail-reservasi')
                            </div>
                            
                            {{-- <a href="">
                                @include('components.button-edit')
                            </a>
                            
                            <a href="">
                                @include('components.button-delete')
                            </a> --}}
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
    
