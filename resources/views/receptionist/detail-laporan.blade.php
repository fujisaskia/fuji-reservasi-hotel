@extends('layouts/receptionist')

@section('title', 'Detail Laporan')

@section('content')
<div class="max-w-3xl bg-white rounded shadow-md mx-auto p-6 text-xs">

        {{-- invoice --}}
        <div class="text-center pb-8 border-b-2 uppercase">
            <h1 class="text-2xl font-semibold">Detail Laporan</h1>
            <h1 class="text-lg">#{{ $reservation->invoice->invoice_number }}</h1>
        </div>

        {{-- guest information --}}
        <div class="my-8">
            <strong class="">Informasi Tamu</strong>
            <div class="flex justify-between  mt-3">
                <div class="space-y-2">
                    <div class="">
                        <strong>Nama</strong>
                        <p>{{ $reservation->user->full_name }}</p>
                    </div>
                    <div class="">
                        <strong>Email</strong>
                        <p>{{ $reservation->user->email }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="">
                        <strong>Telepon</strong>
                        <p>{{ $reservation->user->phone_number }}</p>
                    </div>
                    <div class="">
                        <strong>Tanggal : </strong> 
                        <p>{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

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
                    <td class="px-3 py-2">IDR {{ number_format($roomPricePerNight, '0', ',', ',') }}</td>
                    <td class="px-3 py-2">
                        {{ $reservation->total_room }} <span class="text-[11px]">Kamar</span> 
                        x 
                        {{ $nights }} <span class="text-[11px]">Malam</span>
                    </td>
                    <td class="px-3 py-2 text-right">IDR {{ number_format($reservation->payment->amount, '0', ',', ',') }}</td>
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
                        <td class="px-3 py-2" colspan="3">Tidak ada layanan yang dipesan</td>
                    </tr>
                @endforelse

                <tr class="font-semibold text-gray-800 border-t">
                    <td class="p-2 text-right" colspan="3">Total Layanan</td>
                    <td class="p-2 text-right">IDR {{ number_format($serviceOrderTotal, '0', ',', ',') }}</td>
                </tr>
                <!-- Subtotal -->
                <tr class="font-semibold text-gray-800">
                    <td class="p-2 text-right" colspan="3">Sub Total</td>
                    <td class="p-2 text-right">IDR {{ number_format($grandTotal, '0', ',', ',') }}</td>
                </tr>

                <!-- Grand Total -->
                <tr class="text-base b font-semibold bg-gray-200">
                    <td class="p-2 text-right" colspan="3">Grand Total</td>
                    <td class="p-2 text-right">IDR {{ number_format($grandTotal, '0', ',', ',') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end mt-8">
            <button class="bg-blue-500 hover:bg-blue-600 w-1/4 py-2 px-4 text-white rounded-l-xl">
                Cetak
            </button>
        </div>

</div>

@endsection