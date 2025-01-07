@extends('layouts/receptionist')

@section('title', 'Laporan')

@section('content')
<div class="container mx-auto bg-white py-8 px-4 rounded-lg shadow-md border border-gray-300 text-sm md:text-xs">
    <h1 class="text-2xl font-bold mb-4 text-center">Laporan</h1>

    <!-- Filter dan Search -->
    <div class="flex justify-center items-center mb-4">
        <form action="{{ route('receptionist.reports') }}" method="GET" class="w-full md:w-auto">
            <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-3">
    {{-- Filter waktu --}}
    <div class="flex space-x-3 w-full md:w-auto md:order-1 order-2 mt-2 md:mt-0">
        <select 
            name="year" 
            class="border border-gray-300 rounded p-2 w-full md:w-auto focus:outline-none focus:ring focus:ring-yellow-200"
        >
            <option value="">Tahun</option>
            @foreach(range(2023, 2025) as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
        <select 
            name="month" 
            class="border border-gray-300 rounded p-2 w-full md:w-auto focus:outline-none focus:ring focus:ring-yellow-200"
        >
            <option value="">Bulan</option>
            @foreach(range(1, 12) as $month)
                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                </option>
            @endforeach
        </select>
    </div>


            {{-- Search bar --}}
            <div class="flex items-center w-full md:order-2 order-1 space-x-2 ">
                <input 
                    type="search" 
                    name="search" 
                    placeholder="Cari nama / invoice" 
                    value="{{ request('search') }}" 
                    class="border border-gray-300 rounded p-3 md:p-2 w-full focus:outline-none focus:ring focus:ring-yellow-200"
                >
                <button 
                    type="submit" 
                    class="bg-rose-700 hover:bg-rose-800 focus:scale-95 text-white px-4 py-2 rounded-full text-sm duration-300"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </form>
</div>


    {{-- button untuk cetak --}}
    <div class="flex justify-end mb-5">
        <a href="{{ route('reports.cetak', request()->all()) }}" 
           class="flex items-center justify-center space-x-2 bg-yellow-600 hover:bg-yellow-700 focus:scale-95 text-white px-4 py-2 rounded-l-full text-sm duration-300">
            <i class="fa-solid fa-print"></i>
            <span>Cetak</span>
        </a>
    </div>    

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 overflow-hidden">
            <thead class="bg-rose-100">
                <tr class="text-left">
                    <th class="py-3 px-4 border-b text-center">No</th>
                    <th class="py-3 px-4 border-b">Nama Tamu</th>
                    <th class="py-3 px-4 border-b">Invoice</th>
                    {{-- <th class="py-3 px-4 border-b text-center">Nomor Kamar</th> --}}
                    <th class="py-3 px-4 border-b text-center">Durasi Menginap</th>
                    <th class="py-3 px-4 border-b">Tgl. Check-Out</th>
                    <th class="py-3 px-4 border-b">Total Biaya</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b text-center">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4 border-b">{{ $reservation->user->full_name }}</td>
                    <td class="py-3 px-4 border-b">{{ $reservation->invoice->invoice_number }}</td>
                    {{-- <td class="py-3 px-4 border-b text-center">{{ $reservation->room->room_number }}</td> --}}
                    <td class="py-3 px-4 border-b text-right">
                        {{ \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays($reservation->check_out_date) }} Malam
                    </td>
                    <td class="py-3 px-4 border-b text-center">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
                    <td class="py-3 px-4 border-b text-right">IDR {{ number_format($reservation->invoice->total_amount, 0, ',', ',') }}</td>
                    <td class="py-3 px-4 border-b text-center">
                        <a href="{{ route('detail.report', $reservation->id) }}" class="flex space-x-2 items-center justify-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-md shadow-lg hover:shadow-none">
                            Lihat
                        </a>                        
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 px-4 text-center">Tidak ada data terkait.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
</div>

@endsection
