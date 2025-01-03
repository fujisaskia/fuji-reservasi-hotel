<!-- resources/views/home.blade.php -->
@extends('layouts.receptionist')

@section('title', 'check-in | receptionist')

@section('content')

    <div class="container p-6 bg-white shadow-lg rounded-lg text-xs">
        <h2 class="text-lg font-semibold mb-6">KAMAR NOMOR : {{ $room->room_number }}</h2>
        <form action="{{ route('checkin.process', $room->id) }}" method="POST">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grid 1 -->
                <div class="space-y-4">
                    <!-- Invoice -->
                    <div class="col-span-1">
                        <label for="invoice" class="block text-sm font-semibold text-gray-700"># INVOICE</label>
                        <input id="invoice" type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" readonly />
                    </div>

                    <!-- Detail Kamar -->
                    <div class="col-span-1 bg-yellow-50 p-4 rounded-md border border-yellow-300">
                        <h3 class="text-base font-bold text-rose-900 mb-2">{{ $room->roomType->tipe_kamar }}</h3>
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

                <!-- Grid 2 -->
                <div class="space-y-4">
                    <!-- Nama Tamu -->
                    <div>
                        <label for="reservation" class="block text-sm font-semibold text-gray-700">Nama Tamu</label>
                        <select name="reservation_id" id="reservation" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                            <option value="">Pilih Tamu</option>
                            @foreach ($reservations as $reservation)
                                @php
                                    $invoice = $invoices->firstWhere('reservation_id', $reservation->id);
                                @endphp
                                <option 
                                    value="{{ $reservation->id }}"
                                    data-identification-type="{{ $reservation->user->identification_type }}"
                                    data-identification-number="{{ $reservation->user->identification_number }}"
                                    data-checkin-date="{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}"
                                    data-checkout-date="{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}"
                                    data-invoice-number="{{ $invoice ? $invoice->invoice_number : '' }}"
                                >
                                    {{ $reservation->id }} - {{ $reservation->user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Identitas -->
                    <div class="space-y-2">
                        <div class="flex space-x-2">
                            <input type="text" id="identification_type" name="identification_type" class="w-1/2 block p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Jenis Identitas" readonly>
                            <input type="text" id="identification_number" name="identification_number" class="w-1/2 block p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="Nomor Identitas" readonly>
                        </div>
                    </div>
                    <!-- Deposit -->
                    <div class="space-y-2">
                        <div class="flex space-x-2 items-center">
                            <label for="deposit" class="block text-sm font-semibold text-gray-700">DEPOSIT</label>
                            <input type="number" id="deposit" name="deposit" class="w-full block px-2 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300" placeholder="nominal deposit">
                        </div>
                        <div class="flex items-center space-x-2 text-[10px] p-2 bg-rose-100 border-l-4 border-rose-800">
                            <i class="fa-solid fa-circle-exclamation text-sm text-rose-800"></i>
                            <span>Masukkan Deposit di kamar akhir</span>
                        </div>
                    </div>
                </div>

                <!-- Grid 3 -->
                <div class="space-y-4">
                    <!-- Tanggal Check-In -->
                    <div>
                        <label for="checkin-date" class="block font-semibold text-gray-700">Tanggal / Waktu <span class="text-rose-700">Check-In</span></label>
                        <div class="flex space-x-2">
                            <input id="checkin-date" name="check_in_date" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required />
                            <input type="time" id="checkin-time" value="14:00" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" readonly>
                        </div>
                    </div>

                    <!-- Tanggal Check-Out -->
                    <div>
                        <label for="checkout-date" class="block font-semibold text-gray-700">Tanggal / Waktu <span class="text-rose-700">Check-Out</span></label>
                        <div class="flex space-x-2">
                            <input id="checkout-date" name="check_out_date" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required />
                            <input type="time" id="checkout-time" value="12:00" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end mt-8 space-x-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 font-semibold rounded-md shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 duration-300">
                    Check In
                </button>
            </div>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reservationSelect = document.getElementById('reservation');
            const invoiceInput = document.getElementById('invoice');
            const identificationTypeInput = document.getElementById('identification_type');
            const identificationNumberInput = document.getElementById('identification_number');
            const checkinDateInput = document.getElementById('checkin-date');
            const checkoutDateInput = document.getElementById('checkout-date');

            reservationSelect.addEventListener('change', (event) => {
                const selectedOption = event.target.options[event.target.selectedIndex];

                // Ambil data dari atribut data-*
                const identificationType = selectedOption.getAttribute('data-identification-type') || '';
                const identificationNumber = selectedOption.getAttribute('data-identification-number') || '';
                const checkinDate = selectedOption.getAttribute('data-checkin-date') || '';
                const checkoutDate = selectedOption.getAttribute('data-checkout-date') || '';
                const invoiceNumber = selectedOption.getAttribute('data-invoice-number') || '';

                // Isi input dengan data terkait
                identificationTypeInput.value = identificationType;
                identificationNumberInput.value = identificationNumber;
                checkinDateInput.value = checkinDate;
                checkoutDateInput.value = checkoutDate;
                invoiceInput.value = invoiceNumber;
            });
        });
    </script>

@endsection
