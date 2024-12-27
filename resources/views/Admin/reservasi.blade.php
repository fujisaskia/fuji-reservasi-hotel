<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Reservasi')

@section('content')

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



<div class="container mx-auto bg-white py-8 px-6 shadow-md border border-gray-200 rounded-lg">
    <h2 class="text-2xl font-bold text-center mb-4">Manajemen Reservasi</h2>

    <!-- Filter dan Search -->
    <div class="flex justify-center items-center mb-8 text-xs">
        <form action="{{ route('admin.reservations') }}" method="GET" class="flex items-center space-x-2">
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

    <form method="GET" action="{{ route('admin.reservasi.cetak') }}" class="flex text-xs justify-between mb-5">
        <div class="flex space-x-2 justify-center items-center">
            <p class="font-semibold p-2 hidden md:flex">Filter :</p>
            <div class="grid grid-cols-3 gap-2 md:gap-4">
                <select name="status" id="status-filter" class="border p-2 rounded-md">
                    <option value="">Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Checked-In">Checked-In</option>
                    <option value="Checked-Out">Checked-Out</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            
                <select name="bulan" id="bulan-filter" class="border p-2 rounded-md">
                    <option value="">Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            
                <select name="tahun" id="tahun-filter" class="border p-2 rounded-md">
                    <option value="">Tahun</option>
                    @for ($year = now()->year; $year >= 2020; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>
    
        <button type="submit" class="py-2 px-6 ml-1 text-white bg-yellow-500 hover:bg-yellow-600 rounded-l-xl">Cetak</button>
    </form>
    
    <div class="overflow-x-auto">
        <table id="reservation-table" class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-rose-100 border-b border-gray-300">
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">No</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Nama Tamu</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tipe Kamar</th>                    
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Harga (IDR)</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tgl. Reservasi</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tgl. check-In</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Tgl. check-Out</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Status Reservasi</th>
                    <th class="p-3 lg:p-2 text-left text-sm lg:text-xs font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody id="reservation-body">
                @foreach ($reservations as $index => $reservation)
                <tr class="hover:bg-gray-100 border-b border-gray-300">
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ $index + 1 }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ $reservation->user->full_name }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ $reservation->roomType->tipe_kamar }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">IDR  {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-500">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                    <td class="p-3 lg:p-2 text-sm lg:text-xs text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
                    <td class="p-2 text-[11px] text-white">
                        <form action="{{ route('admin.reservation.confirm', $reservation->id) }}" method="POST">
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
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    </div>
</div>

{{-- <script>
    const filters = {
        status: document.getElementById('status-filter'),
        bulan: document.getElementById('bulan-filter'),
        tahun: document.getElementById('tahun-filter'),
    };

    const tableBody = document.querySelector('#reservation-body');

    function fetchReservations() {
        const params = new URLSearchParams({
            status: filters.status.value,
            bulan: filters.bulan.value,
            tahun: filters.tahun.value,
        });

        fetch(`/reservations?${params.toString()}`)
            .then(response => response.json())
            .then(data => updateTable(data.reservations.data))
            .catch(error => console.error('Error:', error));
    }

    function updateTable(reservations) {
        tableBody.innerHTML = ''; // Kosongkan tabel

        if (reservations.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center p-4 text-gray-500">Tidak ada data ditemukan.</td>
                </tr>
            `;
            return;
        }

        reservations.forEach((reservation, index) => {
            const row = `
                <tr class="hover:bg-gray-100 border-b border-gray-300">
                    <td class="p-3 text-sm text-gray-600">${index + 1}</td>
                    <td class="p-3 text-sm text-gray-600">${reservation.user.full_name}</td>
                    <td class="p-3 text-sm text-gray-600">${reservation.room_type.tipe_kamar}</td>
                    <td class="p-3 text-sm text-gray-600">IDR ${reservation.total_price.toLocaleString()}</td>
                    <td class="p-3 text-sm text-gray-500">${new Date(reservation.reservation_date).toLocaleDateString()}</td>
                    <td class="p-3 text-sm text-gray-600">${new Date(reservation.check_in_date).toLocaleDateString()}</td>
                    <td class="p-3 text-sm text-gray-600">${new Date(reservation.check_out_date).toLocaleDateString()}</td>
                    <td class="p-2 text-[11px] text-white">
                        <span class="p-1 w-full rounded-full ${getStatusClass(reservation.reservation_status)}">
                            ${reservation.reservation_status}
                        </span>
                    </td>
                    <td class="flex space-x-2 p-3 text-sm">
                        <!-- Tambahkan tombol aksi di sini -->
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function getStatusClass(status) {
        switch (status) {
            case 'Pending': return 'bg-yellow-100 text-yellow-700';
            case 'Confirmed': return 'bg-green-100 text-green-600';
            case 'Checked-In': return 'bg-blue-100 text-blue-600';
            case 'Checked-Out': return 'bg-rose-100 text-rose-600';
            case 'Cancelled': return 'bg-red-100 text-red-700';
            default: return '';
        }
    }

    Object.values(filters).forEach(filter => {
        filter.addEventListener('change', fetchReservations);
    });

    document.addEventListener('DOMContentLoaded', fetchReservations);
</script> --}}

@endsection
    
