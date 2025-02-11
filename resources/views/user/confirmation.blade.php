<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<div class="max-w-4xl mx-auto text-sm lg:text-xs p-2 md:p-12 lg:p-0">
    @if(session('message'))
    <div class="alert alert-warning">
        {{ session('message') }}
    </div>
@endif

    
    {{-- <h3 class="text-lg md:text-xl font-semibold mb-6 sticky top-4">Guest Information</h3> --}}
    <div class="">

        <!-- Right Section -->
        <div class="space-y-4">
            {{-- Booking Summary --}}
            <div id="booking-sum" class="p-6 bg-white rounded-b-3xl shadow-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Your booking summary</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 border-t pt-4">
                    <div class="p-4 hover:border-l-2 hover:bg-gray-50 duration-300">
                        <div class="flex justify-end">
                            <button id="openModal" class="underline text-yellow-800 hover:-translate-y-0.5 duration-300">Edit</button>
                        </div>
                        
                        <div class="">
                            <h5 class="font-semibold text-xs mb-3">Tanggal</h5>
                            <div class="flex space-x-3">
                                <span>{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</span>
                                <span>-</span>
                                <span>{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 mt-6 gap-4">
                            <div class="">
                                <h5 class="font-semibold text-xs mb-3">Kamar</h5>
                                <span class="text-xs">{{ $reservation->total_room }}</span>
                            </div>
                            <div class="">
                                <h5 class="font-semibold text-xs mb-3">Tamu</h5>
                                <span class="text-xs">{{ $reservation->total_guest }}</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Cart room 1 --}}
                    <div class="border border-gray-400 rounded-lg p-4 shadow-md hover:shadow-rose-200 duration-300">
                        <h3 class="text-lg font-semibold pb-2 border-b">Perincian Harga</h3>
                        <div class="flex space-x-2 py-3 border-b border-gray-300 md:pb-0 md:border-none">
                            {{-- Room picture --}}
                            <div class="flex-col p-1 text-xs">
                                <img src="{{ asset('assets/deluxe.jpg') }}" alt="Room Image" class="w-20 h-20 rounded-md object-cover mb-1">
                            </div>
                            {{-- Room info --}}
                            <div class="flex-grow">
                                <h4 class="text-sm font-semibold uppercase">{{ $reservation->roomType->tipe_kamar }}</h4>
                                <p class="text-[11px] text-gray-600">IDR {{ number_format($reservation->roomType->harga, 0, ',', ',') }} x <span class="text-rose-900">{{ $reservation->total_room }} kamar x {{ \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays($reservation->check_out_date) }} malam</span></p>
                            </div>
                        </div>                 
                        <div class="flex-1 justify-end text-right py-1">
                            <p class="text-base md:text-lg font-semibold">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</p>
                        </div>
                    </div>
                
                </div>
            
                <div class="flex justify-between items-center border-t mt-4 pt-4">
                    <p class="text-lg font-semibold">Total</p>
                    <p class="text-xl font-semibold">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</p>
                </div>
                <div class="grid grid-cols-3 gap-3 md:w-2/3 ml-auto">
                    <!-- Tombol Batal -->
                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                        class="col-span-1 text-center mt-6 p-2 font-semibold rounded-lg text-rose-900 border-2 border-rose-900 shadow-xl hover:shadow-none focus:scale-95 duration-200"
                        >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="">
                            Batal
                        </button>
                    </form>
                    <!-- Tombol Lanjut Bayar -->
                    <button 
                        id="pay-button-{{ $reservation->id }}" 
                        data-reservation-id="{{ $reservation->id }}" 
                        class="col-span-2 text-center mt-6 p-2 font-semibold rounded-lg bg-black text-white shadow-xl hover:shadow-none border-black duration-200">
                        Lanjut Bayar
                    </button>
                </div>
            </div>      
        </div>

    </div>
</div>


<!-- The Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden px-3">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-lg font-semibold pb-3 border-b text-center">Edit Reservasi</h2>
        <form id="editReservationForm" action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Check-in and Check-out Dates -->
            <div class="flex space-x-3 justify-between mt-4 items-center">
                <div class="text-xs w-full">
                    <label for="check_in_date" class="block text-[11px] uppercase font-semibold">Check-in</label>
                    <input type="date" id="check_in_date" name="check_in_date" value="{{ $reservation->check_in_date }}" class="w-full border p-2 mt-1 rounded">
                </div>
                <div class="text-xs w-full">
                    <label for="check_out_date" class="block text-[11px] uppercase font-semibold">Check-out</label>
                    <input type="date" id="check_out_date" name="check_out_date" value="{{ $reservation->check_out_date }}" class="w-full border p-2 mt-1 rounded">
                </div>
            </div>

            <!-- Number of Room -->
            <div class="flex space-x-3 justify-between mt-4 mb-6 items-center">
                <div class="text-xs w-full">
                    <label for="total_room" class="block text-[11px] font-semibold uppercase">Jumlah Kamar</label>
                    <input type="number" id="total_room" name="total_room" value="{{ $reservation->total_room }}" class="w-full border p-2 mt-1 rounded" min="1">
                </div>
    
                <!-- Number of Guest -->
                <div class="text-xs w-full">
                    <label for="total_guest" class="block text-[11px] font-semibold uppercase">Jumlah Tamu</label>
                    <input type="number" id="total_guest" name="total_guest" value="{{ $reservation->total_guest }}" class="w-full border p-2 mt-1 rounded" min="1">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex space-x-3 justify-between text-xs  items-center pt-4 border-t ">
                <button id="closeModal" class="w-full border-2 border-red-900 text-red-900 hover:bg-red-700 hover:text-white p-2 rounded-md font-semibold duration-200">Close</button>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-2 rounded-md font-semibold">Update</button>
            </div>
        </form>

        <!-- Close Button -->
    </div>
</div>

<script>
    // Get modal element and buttons
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modal = document.getElementById('editModal');

    // Show the modal
    openModalBtn?.addEventListener('click', function() {
        modal?.classList.remove('hidden');
    });

    // Close the modal
    closeModalBtn?.addEventListener('click', function() {
        modal?.classList.add('hidden');
    });

    document.querySelectorAll('[id^="pay-button-"]').forEach(button => {
        button.addEventListener('click', function () {
            const reservationId = this.dataset.reservationId;

            // Fetch Snap Token dari server
            fetch('/payment/snap-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ reservation_id: reservationId }),
            })
            .then(response => response.json())
            .then(data => {
                // Panggil modal Snap Midtrans
                window.snap.pay(data.snapToken, {
                    onSuccess: function (result) {
                        // Kirim data ke server untuk memperbarui status pembayaran
                        fetch("{{ route('payment.success') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ 
                                order_id: result.order_id, 
                                payment_status: 'success' 
                            })
                        })
                        .then(response => response.json().then(data => ({ status: response.status, data }))) // Tangkap status HTTP
                        .then(({ status, data }) => {
                            if (status === 200) {
                                Swal.fire({
                                    title: 'Terimakasih!',
                                    text: 'Reservasi Anda Sukses di Bayar !',
                                    imageUrl: '/assets/card-payment.png',
                                    imageWidth: 200,
                                    imageHeight: 200,
                                    imageAlt: 'Pembayaran',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3085d6',
                                    background: '#f8f9fa',
                                    customClass: {
                                        title: 'text-2xl', // Gunakan kelas Tailwind untuk ukuran teks
                                        text: 'text-base', // Gunakan kelas Tailwind untuk ukuran teks
                                    },
                                }).then(() => {
                                    window.location.href = '/my-booking'; // Redirect ke halaman my-booking
                                });
                            } else {
                                throw new Error(data.message || 'Gagal memperbarui status pembayaran.');
                            }
                        })
                        .catch(error => {
                            console.error('Kesalahan:', error.message);
                            Swal.fire({
                                title: 'Error',
                                text: 'Terjadi kesalahan: ' + error.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    },


                    onPending: function(result) {
                        alert('Menunggu Pembayaran...');
                        window.location.href = '/my-booking'; // Redirect ke halaman my-booking
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal!');
                        console.log(result);
                    }
                });
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>




@endsection


