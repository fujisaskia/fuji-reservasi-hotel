<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'dashboard | receptionist')

@section('content')

    <h4 class="bg-white py-3 px-4 text-sm md:text-lg rounded-lg shadow-md md:w-1/2 mb-8 font-semibold">Haloo... Selamat Datang, <span class="font-semibold text-rose-600">{{ Auth::user()->full_name }}!</span></h4>

    <div class="container bg-white py-8 px-4 md:px-6 rounded-lg items-center justify-center md:justify-start text-xs">
        @include('components.room-status')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- Tabel Guest Check In -->
            <div class="bg-white p-4 shadow-md rounded-md">
                <h3 class="text-lg font-semibold mb-4">Tamu Check In</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead>
                            <tr class="border-b font-semibold">
                                <th class="p-3 text-left text-gray-600">Nama Tamu</th>
                                <th class="p-3 text-left text-gray-600"># Kamar</th>
                                <th class="p-3 text-left text-gray-600">Tgl / Waktu Check-In</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($checkInReservations as $reservation)
                                <tr class="border-b font-medium hover:bg-slate-100">
                                    <td class="p-3 text-left text-gray-600">{{ $reservation->user->full_name }}</td>
                                    <td class="p-3 text-center text-gray-600">{{ $reservation->room->first()->room_number ?? 'tidak diketahui' }}</td>
                                    <td class="p-3 text-left text-gray-600">
                                        {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} - 14:00
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b">
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        Tidak ada Tamu yang Check-In hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between mt-4">
                    <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                        <i class="fa-solid fa-angles-left group-hover:-translate-x-1 duration-500"></i>
                        <span>Previous</span>
                    </button>
                    <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                        <span>Next</span>
                        <i class="fa-solid fa-angles-right group-hover:translate-x-1 duration-500"></i>
                    </button>
                </div>
            </div>

        
            <!-- Tabel Guest Check-Out -->
            <div class="bg-white p-4 shadow-md rounded-md">
                <h3 class="text-lg font-semibold mb-4">Tamu Check Out</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead>
                            <tr class="border-b font-semibold">
                                <th class="p-3 text-left text-gray-600">Nama Tamu</th>
                                <th class="p-3 text-left text-gray-600"># Kamar</th>
                                <th class="p-3 text-left text-gray-600">Tgl / Waktu Check-Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($checkOutReservations as $reservation)
                                <tr class="border-b font-medium hover:bg-slate-100">
                                    <td class="p-3 text-left text-gray-600">{{ $reservation->user->full_name }}</td>
                                    <td class="p-3 text-center text-gray-600">{{ $reservation->room->first()->room_number ?? 'tidak diketahui' }}</td>
                                    <td class="p-3 text-left text-gray-600">
                                        {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }} 12:00
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b">
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        Tidak ada Tamu yang Check-Out hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between mt-4">
                    <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                        <i class="fa-solid fa-angles-left group-hover:-translate-x-1 duration-500"></i>
                        <span>Previous</span>
                    </button>
                    <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                        <span>Next</span>
                        <i class="fa-solid fa-angles-right group-hover:translate-x-1 duration-500"></i>
                    </button>
                </div>
            </div>

        </div>
        

    </div>

@endsection
    
