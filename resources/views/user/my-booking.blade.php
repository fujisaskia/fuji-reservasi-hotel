<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')

<div class="max-w-6xl mx-auto text-sm lg:text-xs p-6 md:p-12 lg:p-0">
    <div class="flex gap-4">
        
        <x-menu-profile></x-menu-profile>
        {{-- booking card --}}
        <div class="flex-1 space-y-3">
            @foreach($reservations as $reservation)
            <div class="flex flex-col w-full border rounded-lg shadow-md p-4 mx-auto mb-4
            @if($reservation->reservation_status === 'Checked-Out') bg-slate-200 
            @else bg-white 
            @endif
            ">
                <div class="pb-4 border-b">
                  <h2 class="font-bold text-sm">{{ $hotelSetting->name }}</h2>
                  <p class="text-[11px] text-gray-600">{{ $hotelSetting->address }}</p>
                </div>
                <div class="hidden md:flex justify-between items-start mt-4">
                    <div class="text-left space-y-3">
                        <p class="text-[11px]">Number Invoice</p>
                        <span class="font-semibold">#{{ $reservation->invoice->invoice_number }}</span>
                    </div>
                    <div class="text-left space-y-3">
                        <p class="text-[11px]">Booking Date</p>
                        <span class="font-semibold">                        
                            {{ \Carbon\Carbon::parse($reservation->payment->payment_date)->format('M d, Y') }} 
                        </span>
                    </div>
                    <div class="text-left space-y-3">
                        <p class="text-[11px]">Amout</p>
                        <span class="font-semibold">IDR {{ number_format($reservation->payment->amount, 0, ',', ',') }}</span>
                    </div>
                    <div class="text-left space-y-3">
                        <p class="text-[11px]">Status</p>
                        <span class="rounded-full font-semibold 
                            {{ $reservation->payment->payment_status === 'success' ? 'text-green-700' : '' }}
                            {{ $reservation->payment->payment_status === 'pending' ? 'text-yellow-600' : '' }}
                            {{ $reservation->payment->payment_status === 'failed' ? 'text-red-600' : '' }}">
                            {{ $reservation->payment->payment_status }}
                        </span>
                    </div>
                    <a href="/my-booking/details/{{ $reservation->id }}" class="md:flex text-xs bg-gray-800 text-white px-4 py-2 rounded-md flex items-center">
                        Detail
                    </a>
                </div>
                <div class="flex md:hidden justify-between items-start my-4">
                    <div class="text-xs text-rose-900 text-left space-y-2">
                        <div class="flex space-x-1">
                            <span class="font-semibold">#{{ $reservation->invoice->invoice_number }}</span>
                        </div>
                        <p class="text-[11px]">
                            {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} 
                            - 
                            {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-xs text-rose-900 text-right space-y-2">
                        <p class="text-xs font-semibold">IDR {{ number_format($reservation->payment->amount, 0, ',', ',') }}</p>
                        <p class="border-2 py-1 px-2 text-center font-semibold
                            {{ $reservation->payment->payment_status === 'success' ? 'text-green-700' : '' }}
                            {{ $reservation->payment->payment_status === 'pending' ? 'text-yellow-600' : '' }}
                            {{ $reservation->payment->payment_status === 'failed' ? 'text-red-600' : '' }}">
                            {{ $reservation->payment->payment_status }}
                        </p>
                    </div>
                </div>
                <a href="/my-booking/details/{{ $reservation->id }}" class="flex md:hidden text-sm bg-gray-800 text-white p-3 rounded-md w-full justify-center text-center items-center focus:scale-95 duration-300">
                    <span>Detail</span>
                </a>
            </div>
            @endforeach
        </div>
        {{-- booking card end --}}
    </div>
</div>


@endsection