<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')



<div class="max-w-6xl mx-auto text-sm md:text-xs p-3 md:p-12 lg:p-0">
    <div class="flex gap-4">
        
        <x-menu-profile></x-menu-profile>
        
        <div class="flex-1 bg-white w-full border rounded-lg shadow-md p-4 mx-auto mb-4 text-sm md:text-xs">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Header Section -->
            <div class="pb-6 border-b">
                <h1 class="font-bold text-sm">Reservasi</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-20 my-6">
                    <!-- Guest Information -->
                    <div class="order-2 md:order-1 space-y-4">
                        <div class="flex justify-between space-x-4">
                            <p class="text-gray-600">Nama</p>
                            <span>{{ $reservation->user->full_name }}</span>
                        </div>
                        <div class="flex justify-between space-x-4">
                            <p class="text-gray-600">Email</p>
                            <span>{{ $reservation->user->email }}</span>
                        </div>
                        <div class="flex justify-between space-x-4">
                            <p class="text-gray-600">Telepon</p>
                            <span>{{ $reservation->user->phone_number }}</span>
                        </div>
                    </div>
                    {{-- Hotel Information --}}
                    <div class="order-1 md:order-2">
                        <p class="text-gray-900 font-bold">{{ $hotelSetting->name }}</p>
                        <p class="text-xs md:text-[11px] text-gray-600">{{ $hotelSetting->address }}</p>
                        <p class="w-1/3 text-sm md:text-xs py-1 px-4 font-semibold mt-2 rounded-r-full  border border-yellow-400 bg-yellow-200 text-yellow-900">{{ $reservation->reservation_status }}</p>
                    </div>
                </div>
            </div>

            <!-- Reservation Details -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 items-start gap-4 my-6">
                <div class="text-left space-y-2">
                    <p class="text-xs text-gray-700">Nomor Invoice</p>
                    <span class="font-semibold text-black">#{{ $reservation->invoice->invoice_number }}</span>
                </div>
                <div class="text-left space-y-2">
                    <p class="text-xs text-gray-700">Tgl. Bayar</p>
                    <span class="font-semibold text-black">
                        {{ \Carbon\Carbon::parse($reservation->payment->payment_date)->format('M d, Y') }}
                    </span>
                </div>
                <div class="text-left space-y-2">
                    <p class="text-xs text-gray-700">Total</p>
                    <span class="font-semibold text-black">IDR {{ number_format($reservation->payment->amount, 0, ',', ',') }}</span>
                </div>
                <div class="text-left space-y-2">
                    <p class="text-xs text-gray-700">Pembayaran</p>
                    <span class="font-semibold italic
                        {{ $reservation->payment->payment_status === 'success' ? 'text-green-700' : '' }}
                        {{ $reservation->payment->payment_status === 'pending' ? 'text-yellow-600' : '' }}
                        {{ $reservation->payment->payment_status === 'failed' ? 'text-red-600' : '' }}">
                        {{ $reservation->payment->payment_status }}
                    </span>
                </div>
            </div>

            <!-- Room Information -->
            <div class="my-6">
                <h2 class="font-bold text-sm mb-3">Room</h2>
                <table class="w-full text-left border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Item</th>
                            <th class="border border-gray-300 px-4 py-2">Harga</th>
                            <th class="border border-gray-300 px-4 py-2">Qty</th>
                            <th class="border border-gray-300 px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">
                                <p class="uppercase">{{ $reservation->roomType->tipe_kamar }}</p>
                                <p class="text-[10px] text-gray-700">
                                    {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}      
                                </p>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">IDR {{ number_format($reservation->roomType->harga, 0, ',', ',') }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $reservation->total_room }}</td>
                            <td class="border border-gray-300 px-4 py-2">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-semibold border border-gray-300 px-4 py-2">Total Harga</td>
                            <td class="text-base border border-gray-300 px-4 py-2 font-bold">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Button cancel --}}
            <div class="flex justify-end mt-12">
                @if ($reservation->reservation_status != 'Checked-In' && $reservation->reservation_status != 'Checked-Out' && $reservation->reservation_status != 'Cancelled')
                    {{-- Tombol aktif untuk membatalkan reservasi --}}
                    @if ($canCancel)
                        <button 
                            onclick="confirmCancelReservation('{{ route('reservation.cancelByGuest', $reservation->id) }}')"
                            class="px-4 py-2 bg-rose-700 text-white hover:bg-rose-600 rounded-t-lg">
                            Batalkan Reservasi
                        </button>
                    @else
                        {{-- Tombol dinonaktifkan --}}
                        <button 
                            class="px-4 py-2 bg-gray-400 text-white cursor-not-allowed rounded-t-lg" 
                            disabled>
                            Batas Waktu Pembatalan Berakhir
                        </button>
                    @endif
                @else
                    {{-- Status Checked-in atau Checked-out: Tidak menampilkan tombol --}}
                    <p class="text-gray-500 hidden">Tombol Pembatalan Tidak Tersedia: Status reservasi Anda sudah {{ $reservation->reservation_status }}.</p>
                @endif
            </div>
                      

        </div>
        
    </div>

    {{-- Tamplate EMail --}}
    {{-- <div class="max-w-xl bg-white w-full border rounded-lg shadow-md p-4 mx-auto mb-4 text-sm md:text-xs mt-12"> --}}
        <!-- Header Section -->
        {{-- <div class="pb-6 border-b">
            <div class="">
                <img src="{{ $hotelSetting->logo_path ? asset('storage/' . $hotelSetting->logo_path) : asset('assets/default-logo.png') }}" 
                alt="{{ $hotelSetting->name ?? 'Default Logo' }}" 
                class="w-40 mx-auto">
            </div>
            <div class="">
                <p class="pb-3 border-b">Halo <span>{{ $reservation->user->full_name }}</span></p>
                <p class="my-2 text-[11px]">Terima kasih telah memilih {{ $hotelSetting->name }} untuk menginap mulai <span class="italic">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('D d F, Y') }} </span>  to <span class="italic">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('D d F, Y') }} </span>.  Kami berharap dapat membuat masa menginap Anda bersama kami menjadi nyaman dan menyenangkan.</p>
            </div>
        </div> --}}
        {{-- Ringkasan Reservasi --}}
        {{-- <div class="">
            <p class="bg-green-500 text-white text-[10px] p-2 mb-8">booking telah di {{ $reservation->reservation_status }}</p>
            <p class="uppercase font-bold text-base pb-2 border-b">Ringkasan Reservasi</p>
            <div class="grid grid-cols-2 gap-8 my-5"> --}}
                <!-- Guest Information -->
                {{-- <div class="">
                    <p class="mb-2 pb-1 border-b">Informasi Tamu</p>
                    <div class="">
                        <div class="flex justify-between space-x-4 ">
                            <p class="text-gray-600">Nama</p>
                            <span>{{ $reservation->user->full_name }}</span>
                        </div>
                        <div class="flex justify-between space-x-4 mt-2">
                            <p class="text-gray-600">Email</p>
                            <a href="{{ $reservation->user->email }}" class="text-blue-600">{{ $reservation->user->email }}</a>
                        </div>
                        <div class="flex justify-between space-x-4 mt-2">
                            <p class="text-gray-600">Telepon</p>
                            <span>{{ $reservation->user->phone_number }}</span>
                        </div>
                    </div>
                </div> --}}
                {{-- Date --}}
                {{-- <div class="">
                    <div class="mb-3">
                        <p class="mb-2 pb-1 border-b">Tanggal</p>
                        <div class="">
                            <p class="text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} 
                                - 
                                {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}      
                            </p>                    <p class="text-[11px] text-gray-600"></p> --}}
                            {{-- <p class="w-1/3 text-[11px] py-1 px-4 font-semibold mt-2 rounded-r-full bg-yellow-400 text-yellow-900">{{ $reservation->reservation_status }}</p> --}}
                        {{-- </div>
                    </div>
    
                    <div class="mb-3">
                        <p class="mb-2 pb-1 border-b">INVOICE</p>
                        <div class="">
                            <p class="text-xs font-semibold">{{ $reservation->invoice->invoice_number }}</p> 
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Room Information -->
        {{-- <div class="my-6">
            <div class="">
                <h3 class="font-semibold pb-1 border-b">Hotel Yang Anda Pilih</h3>
                <div class="my-4">
                    <h2 class="text-base font-bold">{{ $hotelSetting->name }}</h2>
                    <p class="text-[11px]">{{ $hotelSetting->address }}, <span>Phone {{ $hotelSetting->phone }} </span> </p>
                </div>
            </div>  
            <p class="font-semibold mb-1">BIAYA KAMAR :</p>          
            <table class="w-full text-left border-collapse border border-gray-300">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Kamar</th>
                            <th class="text-right border border-gray-300 px-4 py-2">Harga</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Qty</th>
                            <th class="text-right border border-gray-300 px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">
                                <p class="uppercase">{{ $reservation->roomType->tipe_kamar }}</p>
                            </td>
                            <td class="text-right border border-gray-300 px-4 py-2">IDR {{ number_format($reservation->roomType->harga, 0, ',', ',') }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $reservation->total_room }}</td>
                            <td class="text-right border border-gray-300 px-4 py-2">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="text-right">
                        <tr class="">
                            <td colspan="3" class="font-semibold border border-gray-300 px-4 py-2">Total</td>
                            <td class="text-base border border-gray-300 px-4 py-2 font-bold">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="font-semibold border border-gray-300 px-4 py-2">Grand Total</td>
                            <td class="text-lg border border-gray-300 px-4 py-2 font-bold">IDR {{ number_format($reservation->payment->amount, 0, ',', ',') }}</td>
                        </tr>
                    </tfoot>
            </table>
        </div> --}}

        {{-- Kebijakan --}}
        {{-- <div class="font-semibold">
            <p>Kebijakan Tidak Dapat Dikembalikan:</p>
            <li class="text-[10px] px-8 py-4">Jika Anda memilih untuk menjadwal ulang atau membatalkan pemesanan ini, Anda tidak akan menerima pengembalian uang apa pun</li>
        </div> --}}
    {{-- </div> --}}
</div>

<script>
    function confirmCancelReservation(cancelUrl) {
        Swal.fire({
            title: 'Anda yakin ingin membatalkan reservasi ini?',
            text: 'Dengan membatalkan booking ini, anda tidak akan mendapatkan pengembalian data!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Batalkan Reservasi',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = cancelUrl;
            }
        });
    }
</script>



@endsection