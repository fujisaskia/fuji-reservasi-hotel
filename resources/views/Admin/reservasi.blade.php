<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'Manajemen Reservasi')

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
    <h2 class="text-2xl font-bold text-center mb-8">Manajemen Reservasi</h2>

    <div class="flex text-xs justify-between mb-5">
        <form method="GET" action="{{ route('admin.reservations') }}" class="">
            <div class="flex space-x-2 justify-start items-start">
                {{-- <p class="font-semibold p-2 hidden md:flex">Filter :</p> --}}
                <div class="grid grid-cols-4 gap-2">
                    <select name="status" id="status-filter" class="border p-2 rounded-md">
                        <option value="">Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="Checked-In" {{ request('status') == 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
                        <option value="Checked-Out" {{ request('status') == 'Checked-Out' ? 'selected' : '' }}>Checked-Out</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                
                    <select name="tahun" id="tahun-filter" class="border p-2 rounded-md" onchange="updateMonths()">
                        <option value="">Tahun</option>
                        @for ($year = now()->year; $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    
                    <select name="bulan" id="bulan-filter" class="border p-2 rounded-md">
                        <option value="">Bulan</option>
                        @for ($i = 1; $i <= now()->month; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>                              
                </div>

                <div class="flex space-x-2">
                    <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama / invoice" 
                    value="{{ request('search') }}" 
                    class="border border-gray-300 rounded p-2 w-full md:w-full focus:outline-none focus:ring focus:ring-yellow-200"
                    >
                    <button type="submit" class="bg-rose-700 hover:bg-rose-800 focus:scale-95 text-white px-4 py-2 rounded-full text-sm duration-300"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </form>
            {{-- button untuk cetak --}}
            <div class="flex justify-end items-center">
                <a href="{{ route('admin.reservasi.cetak', request()->all()) }}" 
                    class="py-2 px-6 text-white bg-yellow-500 hover:bg-yellow-600 rounded-l-xl justify-center items-center  focus:scale-95 duration-300">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak</span>
                </a>
            </div>
    </div>
        
    <div class="overflow-x-auto">
        <table id="reservation-table" class="min-w-full bg-white border border-gray-200 text-sm md:text-xs">
            <thead>
                <tr class="bg-rose-100 border-b border-gray-300">
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">No</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Nama Tamu</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tipe Kamar</th>                    
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Harga (IDR)</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tgl. Reservasi</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tgl. check-In</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Tgl. check-Out</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Status Reservasi</th>
                    <th class="p-3 lg:p-2 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody id="reservation-body">
                @forelse ($reservations as $reservation)
                <tr class="hover:bg-gray-100 border-b border-gray-300">
                    <td class="p-3 lg:p-2 text-gray-600 text-center">{{ $loop->iteration }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ $reservation->user->full_name }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ $reservation->roomType->tipe_kamar }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">IDR  {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                    <td class="p-3 lg:p-2 text-gray-500">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                    <td class="p-3 lg:p-2 text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
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
                            {{-- Tombol detail reservasi --}}
                            <div x-data="{ openDetailReservasi: false }">
                                <button @click="openDetailReservasi = true">
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
                @empty
                <tr>
                    <td colspan="9" class="py-8 px-4 text-center">Tidak ada data terkait.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
</div>

<script>
    function updateMonths() {
        let selectedYear = document.getElementById('tahun-filter').value;
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth() + 1;
        let monthSelect = document.getElementById('bulan-filter');
        let selectedMonth = "{{ request('bulan') }}"; // Ambil bulan yang sebelumnya dipilih

        monthSelect.innerHTML = '<option value="">Bulan</option>'; 

        let maxMonth = (selectedYear == currentYear) ? currentMonth : 12;

        for (let i = 1; i <= maxMonth; i++) {
            let option = document.createElement('option');
            option.value = i;
            option.textContent = new Intl.DateTimeFormat('id', { month: 'long' }).format(new Date(2023, i - 1));
            if (i == selectedMonth) {
                option.selected = true; // Kembalikan opsi yang sebelumnya dipilih
            }
            monthSelect.appendChild(option);
        }
    }

    document.addEventListener("DOMContentLoaded", updateMonths);
</script>   

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
    
