<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'check-in | receptionist')

@section('content')

    {{-- <a href="" class="flex w-40 bg-green-500 space-x-2 text-center text-white justify-center items-center font-semibold text-sm lg:text-xs rounded-t-lg hover:bg-green-600">
        <i class="fa-solid fa-plus"></i>
       <p class="py-1">Extend</p>        
    </a> --}}

    
    <div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-lg text-xs">
        <h2 class="uppercase text-lg font-semibold mb-4">KAMAR NOMOR : {{ $room->room_number }}</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
            {{-- grid 1 --}}
            <div class="space-y-4">
                <!-- Invoice -->
                <div class="col-span-1">
                    <label for="invoice" class="block text-sm font-semibold text-gray-700"># INVOICE</label>
                    <input type="text" id="invoice" value="{{ $reservation->invoice->invoice_number }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                    <!-- Detail Kamar -->
                <div class="col-span-1 bg-rose-100 p-4 rounded-md">
                    <h3 class="text-base font-bold text-rose-600 mb-2">{{ $room->roomType->tipe_kamar }}</h3>
                    <div class="text-[11px] leading-relaxed">
                        <div class="flex justify-between">
                            <p class="">Harga / Malam :</p>
                            <span class="font-bold text-left">IDR {{ number_format($room->roomType->harga, 0, ',', ',') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <p>Max. Tamu:</p>
                            <span class="font-bold text-left">{{ $room->roomType->kapasitas }} Orang</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- grid 2 --}}
            <div class="space-y-4">
                <div class="mb-2">
                    <label for="nama-tamu" class="block text-sm lg:text-xs font-semibold text-gray-700">Nama Tamu</label>
                    <div class="flex space-x-1">
                        <input id="salutation" value="{{ $reservation->user->title }}" class="mt-1 w-20 block p-3 lg:p-2 border border-gray-300 rounded-lg">
                        <input type="text" id="first-name" value="{{ $reservation->user->full_name }}" class="mt-1 block w-full p-3 lg:p-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                    <!-- Identitas -->
                    <div class="space-y-2">
                        <div class="flex space-x-2">
                            <input type="text" id="identification_type" value="{{ $reservation->user->identification_type }}" name="identification_type" class="w-1/3 block p-2 border border-gray-300 rounded-lg" readonly>
                            <input type="text" id="identification_number" value="{{ $reservation->user->identification_number }}" name="identification_number" class="w-full block p-2 border border-gray-300 rounded-lg" readonly>
                        </div>
                    </div>
                    <!-- Deposit -->
                    <div class="space-y-2">
                        <div class="flex space-x-2 items-center">
                            <label for="deposit" class="block text-sm font-semibold text-gray-700">DEPOSIT</label>
                            <span type="number" id="deposit" name="deposit" value="" class="w-full block px-2 py-3 border border-gray-300 rounded-lg"> {{ number_format($reservation->invoice->deposit ?? 0, 0, ',', ',') }} </span>
                        </div>
                    </div>
            </div>

            {{-- grid 3 --}}
            <div class="space-y-4">
                <!-- Tanggal / Waktu Check-in -->
                <div>
                    <label for="checkin-date" class="block text-sm lg:text-xs font-semibold text-gray-700">Tanggal / Waktu <span class="text-rose-700">Check-In</span></label>
                    <div class="flex space-x-2">
                        <input id="checkin-date" value="{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="time" id="checkin-time" value="14:00" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
        
                <!-- Tanggal / Waktu Check-out -->
                <div>
                    <label for="checkout-date" class="block text-sm lg:text-xs font-semibold text-gray-700">Tanggal / Waktu <span class="text-rose-700">Check-Out</span></label>
                    <div class="flex space-x-2">
                        <input id="checkout-date" value="{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="time" id="checkout-time" value="12:00" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

            </div>

        </div>

        <div class="border-t my-8 border-gray-300 w-full mx-auto"></div>

        <h1 class="text-lg font-semibold mb-4">Rincian Tagihan</h1>

        <div class="lg:px-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3">Keterangan</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Qty</th>
                        <th class="p-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Pemisah Kamar -->
                    <tr class="border-b font-semibold text-gray-600">
                        <td class="px-3 py-2" colspan="4">Kamar</td>
                    </tr>
                    <!-- Kamar -->
                    <tr>
                        <td class="px-3 py-2">{{ $reservation->roomType->tipe_kamar }}</td>
                        <td class="px-3 py-2 ">IDR {{ number_format($reservation->roomType->harga, '0', ',', ',') }}</td>
                        <td class="px-3 py-2">
                            {{ $reservation->total_room }} <span class="text-[11px]">Kamar</span> 
                            x 
                            {{ $nights }} <span class="text-[11px]">Malam</span>
                        </td>
                        <td class="px-3 py-2  text-green-600 text-right">IDR {{ number_format($reservation->payment->amount, '0', ',', ',') }} <span class="text-[11px]">Lunas</span></td>
                    </tr>
            
                    <!-- Pemisah Layanan -->
                    <tr class="border-b font-semibold text-gray-600">
                        <td class="px-3 py-2" colspan="4">Layanan</td>
                    </tr>
                    <!-- Layanan -->
                    @forelse ($reservation->serviceOrders as $order)                    
                        <tr>
                            <td class="px-3 py-2">{{ $order->service->name }}</td>
                            <td class="px-3 py-2">IDR {{ number_format($order->price, '0', ',', ',') }}</td>
                            <td class="px-3 py-2">{{ $order->quantity }}</td>
                            <td class="px-3 py-2 text-right">IDR {{ number_format($order->total_price) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-6 text-center mx-auto text-gray-600" colspan="4">Tidak ada layanan yang dipesan</td>
                        </tr>
                    @endforelse
            
                    <tr class="font-semibold text-right text-gray-800 border-t">
                        <td class="p-2" colspan="3">Total Layanan :</td>
                        <td class="p-2">IDR {{ number_format($serviceOrderTotal, '0', ',', ',') }}</td>
                    </tr>
            
                    <!-- Deposit -->
                    <tr class="font-semibold text-right text-gray-800">
                        <td class="p-2" colspan="3">Deposit :</td>
                        <td class="p-2">IDR {{ number_format($deposit, '0', ',', ',') }}</td>
                    </tr>
            
                    <!-- Kembalian Deposit atau Pembayaran Tambahan -->
                    @if ($additionalPaymentRequired > 0)
                        <tr class="font-semibold text-right text-base text-red-600 bg-gray-200">
                            <td class="p-2" colspan="3">Tambahan Pembayaran</td>
                            <td class="p-2">IDR {{ number_format($additionalPaymentRequired, '0', ',', ',') }}</td>
                        </tr>
                    @elseif ($remainingDeposit > 0)
                        <tr class="font-semibold text-right text-base text-green-600 bg-gray-200 ">
                            <td class="p-2" colspan="3">Kembalian Deposit</td>
                            <td class="p-2">IDR {{ number_format($remainingDeposit, '0', ',', ',') }}</td>
                        </tr>
                    @endif
            
                    <!-- Grand Total -->
                    {{-- <tr class="text-base text-right font-semibold bg-gray-200">
                        <td class="p-2 " colspan="3">Grand Total</td>
                        <td class="p-2">IDR {{ number_format($grandTotal, '0', ',', ',') }}</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
        
        <!-- Buttons -->
        <div class="flex justify-end mt-12 space-x-4 text-sm lg:text-xs">
            <form action="{{ route('checkout.process', $reservation->id) }}" method="POST">
                @csrf
                <button class="bg-rose-500 text-white px-4 py-2 font-semibold rounded-md shadow-md hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500 hover:-translate-y-1 duration-300">
                    Check Out
                </button>
            </form>
             
            <a href="{{ route('invoice.print', $reservation->id) }}" 
                class="flex space-x-2 bg-blue-500 items-center text-white px-4 py-2 font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:-translate-y-1 duration-300">
                 <i class="fa-solid fa-print"></i>
                 <p>Cetak <span class="uppercase tracking-wide ml-1">invoice</span></p>
             </a>     
        </div>
    </div>
  

@endsection
    
