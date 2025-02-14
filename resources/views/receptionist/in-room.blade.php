<!-- resources/views/home.blade.php -->
@extends('layouts.receptionist')

@section('title', 'check-in | receptionist')

@section('content')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: true,
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: '{{ $errors->first() }}',
                showConfirmButton: true,
            });
        </script>
    @endif


    <div
        class="container lg:max-w-5xl mx-auto bg-white py-8 px-4 rounded-lg shadow-md border border-gray-300 text-sm md:text-xs">
        <h2 class="text-lg mb-6 uppercase text-gray-900">- Tipe Kamar : <span class="font-semibold text-rose-900">{{ $roomType->tipe_kamar }}</span> - </h2>
        <form action="{{ route('checkin.process') }}" method="POST" id="checkin-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Grid 1 -->
                <div class="space-y-4">
                    <!-- Invoice -->
                    <div class="col-span-1">
                        <label for="invoice" class="block text-sm font-semibold text-gray-700"># INVOICE</label>
                        <input id="invoice" type="text"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                            readonly />
                    </div>

                    <!-- Detail Kamar -->
                    <div class="col-span-1 bg-yellow-50 p-4 rounded-md border border-yellow-300">
                        <h3 class="text-base font-bold text-rose-900 mb-2">{{ $roomType->tipe_kamar }}</h3>
                        <div class="text-[11px] leading-relaxed">
                            <div class="flex justify-between mb-2">
                                <div class="">
                                    <p class="font-bold text-left">IDR <span class="">{{ number_format($roomType->harga, 0, ',', ',') }}</span></p>
                                    <p class="md:text-[10px]"><span id="total-room">1</span > Kamar x <span id="total-nights">1</span> Malam</p>
                                </div>
                                <p class="text-sm font-bold text-left text-rose-800">IDR <span class="" id="total-price">{{ number_format($roomType->harga, 0, ',', ',') }}</span></p>
                            </div>
                            <div class="flex justify-between">
                                <p>Max. Tamu:</p>
                                <span class="font-bold text-left"><span id="total-guest">{{ $roomType->kapasitas }}</span> Orang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2 -->
                <div class="space-y-4">
                    <!-- Nama Tamu -->
                    <div>
                        <label for="reservation" class="block text-sm font-semibold text-gray-700">Nama Tamu</label>
                        <select name="reservation_id" id="reservation"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                            <option value="">Pilih Tamu</option>
                            @foreach ($reservations as $reservation)
                                @php
                                    $invoice = $invoices->firstWhere('reservation_id', $reservation->id);
                                @endphp
                                <option value="{{ $reservation->id }}"
                                    data-identification-type="{{ $reservation->user->identification_type }}"
                                    data-identification-number="{{ $reservation->user->identification_number }}"
                                    data-checkin-date="{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}"
                                    data-checkout-date="{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}"
                                    data-invoice-number="{{ $invoice ? $invoice->invoice_number : '' }}"
                                    data-total-room="{{ $reservation->total_room }}"
                                    data-total-guest="{{ $reservation->total_guest }}"
                                    data-total-price="{{ number_format($reservation->total_price, 0, ',', ',') }}"
                                    data-total-nights="{{ $reservation->nights }}">
                                    {{ $reservation->user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Identitas -->
                    <div class="space-y-2">
                        <div class="flex space-x-2">
                            <input type="text" id="identification_type" name="identification_type"
                                class="w-1/2 block p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                placeholder="Jenis Identitas" readonly>
                            <input type="text" id="identification_number" name="identification_number"
                                class="w-1/2 block p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                placeholder="Nomor Identitas" readonly>
                        </div>
                    </div>
                    <!-- Deposit -->
                    <div class="space-y-2">
                        <div class="flex space-x-2 items-center">
                            <label for="deposit" class="block text-sm font-semibold text-gray-700">DEPOSIT</label>
                            <input type="number" id="deposit" name="deposit"
                                class="w-full block px-2 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                placeholder="nominal deposit">
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
                        <label for="checkin-date" class="block font-semibold text-gray-700">Tanggal / Waktu <span
                                class="text-rose-700">Check-In</span></label>
                        <div class="flex space-x-2">
                            <input id="checkin-date" name="check_in_date"
                                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                required />
                            <input type="time" id="checkin-time" value="14:00"
                                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                readonly>
                        </div>
                    </div>

                    <!-- Tanggal Check-Out -->
                    <div>
                        <label for="checkout-date" class="block font-semibold text-gray-700">Tanggal / Waktu <span
                                class="text-rose-700">Check-Out</span></label>
                        <div class="flex space-x-2">
                            <input id="checkout-date" name="check_out_date"
                                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                required />
                            <input type="time" id="checkout-time" value="12:00"
                                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-lg mb-6 uppercase text-gray-900 font-semibold">- Pilih Kamar - </h2>

            <table class="min-w-full bg-white border border-gray-300 mb-5">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-3 px-4 border">No. Kamar</th>
                        <th class="py-3 px-4 border">Status</th>
                        <th class="py-3 px-4 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $index => $room)
                        <tr class="border hover:bg-gray-100">
                            <td class="py-3 px-4 text-center">
                                {{ $roomType->tipe_kamar }} - {{ $room->room_number }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                {{ $room->room_status }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <label class="flex items-center justify-center">
                                    <input type="checkbox" name="room_id[]" value="{{ $room->id }}"
                                        class="room-checkbox">
                                    <span class="ml-2">Pilih</span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>



            <!-- Buttons -->
            <div class="flex justify-end mt-8 space-x-4">
                <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 font-semibold rounded-md shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 duration-300">
                    Check In
                </button>
            </div>
        </form>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const reservationSelect = document.getElementById("reservation");
            const invoiceInput = document.getElementById("invoice");
            const identificationTypeInput = document.getElementById("identification_type");
            const identificationNumberInput = document.getElementById("identification_number");
            const checkinDateInput = document.getElementById("checkin-date");
            const checkoutDateInput = document.getElementById("checkout-date");
            const totalRoomElement = document.getElementById("total-room");
            const totalGuestElement = document.getElementById("total-guest");
            const totalPriceReservationElement = document.getElementById("total-price");
            const totalNightsReservationElement = document.getElementById("total-nights");

            if (reservationSelect) {
                reservationSelect.addEventListener("change", (event) => {
                    const selectedOption = event.target.options[event.target.selectedIndex];

                    identificationTypeInput.value = selectedOption.getAttribute(
                        "data-identification-type") || "";
                    identificationNumberInput.value = selectedOption.getAttribute(
                        "data-identification-number") || "";
                    checkinDateInput.value = selectedOption.getAttribute("data-checkin-date") || "";
                    checkoutDateInput.value = selectedOption.getAttribute("data-checkout-date") || "";
                    invoiceInput.value = selectedOption.getAttribute("data-invoice-number") || "";
                    totalRoomElement.textContent = selectedOption.getAttribute("data-total-room") || "";
                    totalGuestElement.textContent = selectedOption.getAttribute("data-total-guest") || "";
                    totalPriceReservationElement.textContent = selectedOption.getAttribute("data-total-price") || "";
                    totalNightsReservationElement.textContent = selectedOption.getAttribute("data-total-nights") || "";
                });
            }

            document.querySelectorAll(".select-room").forEach(button => {
                button.addEventListener("click", function() {
                    let selectedRoomId = this.getAttribute("data-room-id");
                    document.getElementById("selected-room").value = selectedRoomId;

                    document.querySelectorAll(".select-room").forEach(btn => btn.classList.remove(
                        "bg-blue-700"));
                    this.classList.add("bg-blue-700");
                });
            });

            document.getElementById("checkin-form").addEventListener("submit", async function(event) {
                event.preventDefault();

                let formData = new FormData(this);
                let selectedRooms = [];

                // Ambil semua checkbox yang dicentang
                document.querySelectorAll(".room-checkbox:checked").forEach(checkbox => {
                    selectedRooms.push(checkbox.value);
                });

                if (selectedRooms.length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Pilih Kamar!",
                        text: "Silakan pilih setidaknya satu kamar sebelum check-in.",
                        showConfirmButton: true,
                    });
                    return;
                }

                // Tambahkan reservation_id jika ada
                let reservationId = document.getElementById("reservation").value;
                formData.append("reservation_id", reservationId);

                // Kirim setiap room_id sebagai array
                selectedRooms.forEach(roomId => {
                    formData.append("room_id[]", roomId);
                });

                try {
                    let response = await fetch(this.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']")
                                .getAttribute("content"),
                            "Accept": "application/json"
                        },
                    });

                    let data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || "Terjadi kesalahan.");
                    }

                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: data.message || "Check-in berhasil!",
                        showConfirmButton: true,
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });

                } catch (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.message || "Terjadi masalah saat menghubungi server.",
                        showConfirmButton: true,
                    });
                }
            });
        });
    </script>

@endsection
