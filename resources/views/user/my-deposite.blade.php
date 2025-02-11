<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')

<div class="max-w-6xl mx-auto text-sm lg:text-xs p-6 md:p-12 lg:p-0 min-h-screen">
    <div class="flex gap-4">
        
        <x-menu-profile></x-menu-profile>
        {{-- active deposite card --}}

        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-800 text-center capitalize my-5">Deposit Details</h2>
            @if ($reservations->where('reservation_status', 'Checked-In')->count() > 0)
            @foreach ($reservations->where('reservation_status', 'Checked-In') as $reservation)
            <div class="flex flex-col w-full max-w-2xl border rounded-xl shadow-lg p-6 mx-auto mb-6 bg-white">
                <div class="space-y-3">
                    <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <p class="text-gray-700 font-semibold text-gray-900">No. Invoice :</p>
                                <span class="text-gray-800 text-sm font-mono">{{ $reservation->invoice->invoice_number }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-gray-700 font-semibold text-gray-900">Tipe Kamar :</p>
                                <span class="text-gray-800 uppercase">{{ $reservation->roomType->tipe_kamar }}</span>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center justify-between">
                                <p class="text-gray-700 font-semibold text-gray-900">Tanggal :</p>
                                <span class="text-gray-800">
                                    {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex bg-gray-100 p-3 rounded-lg justify-between md:text-sm font-semibold shadow-sm">
                        <p>Jumlah Pembayaran :</p>
                        <span>IDR {{ number_format($reservation->invoice->deposit, '0', ',', ',') }}</span>
                    </div>
                </div>
            
                <div class="mt-6">
                    <h3 class="text-center text-sm font-semibold text-gray-900">- Rincian Tagihan -</h3>
                    <div class="overflow-x-auto mt-2 bg-white shadow-md">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Deskripsi</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-700">qty</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-700">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($reservation->serviceOrders->count() > 0)
                                @foreach ($reservation->serviceOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-600">{{ $order->service->name }}</td>
                                        <td class="px-4 py-3 text-gray-600 text-center">{{ $order->quantity }}</td>
                                        <td class="px-4 py-3 text-gray-600 text-right">IDR {{ number_format($order->total_price, '0', ',', ',') }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="p-6 text-gray-600 text-center">Tidak ada layanan tambahan</td>
                                        </tr>
                                    @endif
                                    <tr class="bg-gray-100 font-semibold">
                                        <td colspan="2" class="px-4 py-3 text-gray-800">Total</td>
                                        <td class="px-4 py-3 text-gray-700 text-right">IDR {{ number_format($totalHarga, '0', ',', ',') }}</td>
                                    </tr>
                                    <tr class="bg-gray-100 font-semibold">
                                        <td colspan="2" class="px-4 py-3 text-gray-800">Deposit</td>
                                        <td class="px-4 py-3 text-gray-800 text-right">IDR {{ number_format($reservation->invoice->deposit, '0', ',', ',') }}</td>
                                    </tr>
                                    <!-- Kembalian Deposit atau Pembayaran Tambahan -->
                                    @if ($additionalPaymentRequired > 0)
                                        <tr class="font-semibold text-red-600 text-sm">
                                            <td colspan="2" class="px-4 py-3">Tambahan Pembayaran</td>
                                            <td  class="px-4 py-3 text-right">IDR {{ number_format($additionalPaymentRequired, '0', ',', ',') }}</td>
                                        </tr>
                                    @elseif ($remainingDeposit > 0)
                                        <tr class="font-semibold text-green-600 text-sm ">
                                            <td colspan="2" class="px-4 py-3">Kembalian Deposit</td>
                                            <td  class="px-4 py-3 text-right">IDR {{ number_format($remainingDeposit, '0', ',', ',') }}</td>
                                        </tr>
                                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center px-4 space-y-2">
                    <h3 class="text-sm font-semibold text-gray-900 mt-5">- Catatan -</h3>
                    <q class="text-[10px]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quod quaerat sapiente, doloremque amet delectus facilis quisquam fuga soluta consectetur voluptates possimus excepturi non ad necessitatibus id, aliquam maiores dignissimos.</q>
                </div>
            </div>
            @endforeach
            @else
                <p class="text-center text-gray-600 font-semibold py-12">Tidak ada reservasi check-in</p>
            @endif
            
            {{-- <div class="flex justify-center mt-12">
                <a href="#" class=" bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-900 transition">
                    Lihat Riwayat Deposit
                </a>
            </div> --}}
        </div>
        

        {{-- active deposite card end --}}
    </div>
</div>


@endsection