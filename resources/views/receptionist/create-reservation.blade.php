<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'Create Reservation By Hotel')

@section('content')

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<div class="container md:max-w-5xl mx-auto bg-white p-6 rounded-lg text-sm md:text-xs shadow-md">
    <h2 class="text-xl text-center py-3 font-semibold border-b">Buat Reservasi</h2>
    <form id="reservation-form" method="POST" action="{{ route('reservations.create-by-hotel') }}" class="mt-5 ">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="">
                    <div class="flex space-x-2 relative">
                        <!-- Input untuk nama tamu -->
                        <input type="text" id="name" name="name" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-yellow-100" placeholder="Nama Tamu" required autocomplete="off">
                        <input type="hidden" id="user_id" name="user_id">
                        
                        <!-- Tombol untuk membuat akun -->
                        <a href="/create-account-guest">
                            <button type="button" class="bg-yellow-300 hover:bg-yellow-400 px-3 py-2 rounded-t-lg hover:-translate-y-0.5 focus:scale-95 duration-300">
                                Buat Akun
                            </button>
                        </a>
            
                        <!-- Dropdown untuk saran autocomplete -->
                        <div id="name-suggestions" class="absolute left-0 right-0 mt-8 bg-white border border-gray-300 rounded-md shadow-md z-10 hidden">
                            <!-- Saran nama akan ditampilkan di sini -->
                        </div>
                    </div>
                </div>
                <div class="">
                    <!-- Input untuk email -->
                    <input type="email" id="email" name="email" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-yellow-100" placeholder="Email Tamu" required readonly>
                </div>
            </div>
            
            

            {{-- kolom input check-in / Out --}}
            <div class="">
                <div class="space-x-3 flex pb-4">
                    {{-- input tanggal check-in --}}
                    <div>
                        <input type="text" id="check-in" name="check_in_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-yellow-100" placeholder="Pilih Check-in Date" required>
                    </div>
                    {{-- input tanggal check-out --}}
                    <div>
                        <input type="text" id="check-out" name="check_out_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-yellow-100" placeholder="Pilih Check-out Date" required>
                    </div>
                </div>
                <div class="space-x-3 flex pb-4 border-b">
                    {{-- input jumlah tamu --}}
                    <div>
                        <input type="number" id="total_guest" name="total_guest" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-yellow-100" placeholder="Jumlah Tamu" required>
                    </div>
                    <div class="flex items-center px-3 py-2 mb-4 text-red-800 rounded-lg bg-red-50 text-[11px]" role="alert">
                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <span class="font-medium">Isi setelah memilih tipe kamar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Kamar Tersedia -->
        <div id="room-table" class="py-4">
            <div class="flex items-center p-2 mb-4 text-blue-800 rounded-lg bg-blue-50 text-[11px]" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Pilih Tipe Kamar</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 text-sm md:text-xs">
                    <thead class="bg-gray-200">
                        <tr class="text-left">
                            <th class="px-4 py-3 md:py-2 border-b text-center">No</th>
                            <th class="px-4 py-3 md:py-2 border-b">Tipe Kamar</th>
                            <th class="px-4 py-3 md:py-2 border-b">Kapasitas</th>
                            <th class="px-4 py-3 md:py-2 border-b">Harga</th>
                            <th class="px-4 py-3 md:py-2 border-b">Quantity</th>
                            <th class="px-4 py-3 md:py-2 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    @foreach ($availableRoom as $index => $roomType)                
                        <tbody id="room-table-body" class="text-left hover:bg-gray-50">
                            <td class="px-4 py-2 border-b text-center">{{ $index +1 }}</td>
                            <td class="px-4 py-2 border-b">{{ $roomType->tipe_kamar }}</td>
                            <td class="px-4 py-2 border-b">{{ $roomType->kapasitas }} Orang</td>
                            <td class="px-4 py-2 border-b">IDR {{ number_format($roomType->harga, 0, ',', ',') }}<span class="text-[10px] text-gray-600">/kamar/malam</span></td>
                            <td class="px-4 py-2 border-b text-center">
                                <input type="number" min="1"  value="1" name="total_room" class="w-16 p-2 border border-gray-300 rounded-md text-center">
                                <input type="hidden" id="room_type_id" name="room_type_id">
                            </td>
                            <td class="px-4 py-2 border-b text-center">
                                <button 
                                    data-room-id="{{ $roomType->id }}" 
                                    data-room-name="{{ $roomType->tipe_kamar }}" 
                                    data-room-price="{{ $roomType->harga }}" 
                                    class="select-room bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md focus:scale-95 duration-300">
                                    Pilih
                                </button>
                            </td>                            
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>

        <input type="hidden" id="hidden_total_room" name="total_room">


        {{-- Rincian Pesanan Kamar --}}
        <div id="order-summary" class="mt-4 hidden">
            <div class="flex items-center p-2 mb-4 text-rose-800 rounded-lg bg-rose-50 text-[11px]" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <div>
                    <span class="font-medium">Tipe Kamar yang dipilih:</span>
                </div>
            </div>
            <div class="p-4 bg-gray-100 mb-5 rounded-b-lg">
                <div class="flex justify-between text-sm text-gray-700 font-semibold">
                    <div class="flex flex-col">
                        <span id="room-type-name">Tipe Kamar</span>
                        <span class="text-[11px] text-gray-500" id="room-details">-</span>
                    </div>
                    <span class="text-lg"><span class="text-rose-900" id="room-price">-</span></span>
                </div>
            </div>
        </div>
        

        {{-- button untuk membuat reservasi --}}
        <div class="flex justify-end mt-6">
            <button type="submit" class="py-2 px-4 bg-green-500 hover:bg-green-600 rounded-md text-white mt-5 focus:scale-95 duration-300">Buat Reservasi</button>
        </div>
    </form>
</div>

<!-- Modal untuk memilih metode pembayaran -->
<div id="reservation-modal"  class="fixed inset-0 bg-black bg-opacity-50 z-50 flex hidden items-center justify-center px-6">
    <div  class="bg-white p-6 rounded-lg shadow-lg w-full md:w-1/3">
        <div id="cancel-create-reservation-btn" class="relative flex justify-end">
            <button class="px-4 py-2 hover:bg-gray-200 text-gray-900 hover:text-gray-800 border border-blue-300 rounded-full focus:ring focus:ring-blue-300">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <h2 class="text-lg text-gray-800 text-center mb-4 pb-1 border-b border-gray-300 font-semibold">Konfirmasi Reservasi</h2>

        {{-- id reservasi tersembunyi --}}
        <span class="reservation-id hidden"></span>

        {{-- Guest information --}}
        <div class="p-4 bg-gray-100 mb-2 rounded-t-lg">
            <div class="">
                <h4 class="text-xs text-gray-800 rounded uppercase mb-1">informasi Tamu :</h4>
                <div class="flex flex-col text-sm text-gray-700 font-semibold">
                    <span class="guest-name">User Name</span>
                    <span class="guest-email text-xs">user-email@gmail.com</span>
                </div>
            </div>
        </div>
        {{-- Check-in date --}}
        <div class="p-4 bg-gray-100 mb-2">
            <div class="">
                <h4 class="text-xs text-gray-800 rounded uppercase mb-1">Tanggal dipilih :</h4>
                <span class="text-sm text-gray-700 font-semibold"><span class="check-in-date">Check-In Date</span> - <span class="check-out-date">Check-Out Date</span></span>
            </div>
        </div>
        {{-- Total Room & Guest--}}
        <div class="flex justify-between space-x-2 mb-2">
            <div class="bg-gray-100  p-4 w-full rounded-l-lg">
                <h4 class="text-xs text-gray-800 rounded uppercase mb-1">Jumlah Kamar :</h4>
                <span class="total-room text-sm text-gray-700 font-semibold">0 Kamar</span>
            </div>
            <div class="bg-gray-100  p-4 w-full rounded-r-lg">
                <h4 class="text-xs text-gray-800 rounded uppercase mb-1">Jumlah Tamu :</h4>
                <span class="total-guest text-sm text-gray-700 font-semibold">0 Tamu</span>
            </div>
        </div>
        {{-- room confirmation --}}
        <div class="p-4 bg-gray-100 mb-5 rounded-b-lg">
            <div class="">
                <h4 class="text-xs text-gray-800 rounded uppercase mb-1">Kamar</h4>
                <div class="flex justify-between text-sm text-gray-700 font-semibold">
                    <div class="flex flex-col">
                        <span class="room-type">Tipe Kamar</span>
                        {{-- <span class="text-[11px] text-gray-400">0 kamar x 0 malam</span> --}}
                    </div>
                    <span class="total-price text-rose-900">000,000,000</span>
                </div>
            </div>
        </div>
        <h3 class="text-base md:text-sm font-semibold mb-4 text-center pt-4 border-t border-gray-300 ">Pilih Metode Bayar</h3>
        <div class="flex justify-between space-x-4 text-sm md:text-xs">
            <button class="cash-button w-full text-gray-800 bg-gray-200 px-4 py-3 rounded-md text-center hover:bg-gray-300 focus:scale-95 duration-300">Cash</button>
            <button class="digital-button w-full text-white bg-rose-700 px-4 py-3 rounded-md text-center hover:bg-rose-600 focus:scale-95 duration-300">Digital</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputName = document.getElementById('name');
        const suggestionsBox = document.getElementById('name-suggestions');
        const emailInput = document.getElementById('email');
        const userIdInput = document.getElementById('user_id');

        const users = @json($users); // Mengambil daftar user dari backend

        inputName.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            suggestionsBox.innerHTML = ''; // Kosongkan saran sebelumnya
            if (query) {
                const matches = users.filter(user => user.full_name.toLowerCase().includes(query));
                if (matches.length > 0) {
                    matches.forEach(user => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.textContent = user.full_name;
                        suggestionItem.className = 'px-3 py-2 cursor-pointer hover:bg-gray-200';

                        suggestionItem.addEventListener('click', function (e) {
                            e.stopPropagation();
                            inputName.value = user.full_name;
                            emailInput.value = user.email;
                            userIdInput.value = user.id; // Simpan ID user di input hidden
                            suggestionsBox.classList.add('hidden'); // Sembunyikan saran
                        });

                        suggestionsBox.appendChild(suggestionItem);
                    });
                    suggestionsBox.classList.remove('hidden');
                } else {
                    suggestionsBox.classList.add('hidden');
                }
            } else {
                suggestionsBox.classList.add('hidden');
            }
        });

        document.addEventListener('click', function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== inputName) {
                suggestionsBox.classList.add('hidden');
            }
        });
    });

    let roomTypeId = null;

    // Handle room selection
    document.querySelectorAll('.select-room').forEach(button => {
        button.addEventListener('click', function () {
            // Ambil baris tabel yang sesuai dengan tombol yang diklik
            const selectedRow = this.closest('tr');
            const totalRoomsInput = selectedRow.querySelector('input[name="total_room"]');

            if (!totalRoomsInput) {
                alert("Input jumlah kamar tidak ditemukan.");
                return;
            }

            // Ambil nilai dari input jumlah kamar yang sesuai
            const totalRooms = totalRoomsInput.value;

            // Ambil detail kamar dari atribut tombol
            roomTypeId = this.getAttribute('data-room-id');
            const roomTypeName = this.getAttribute('data-room-name');
            const roomPrice = parseFloat(this.getAttribute('data-room-price')) || 0;
            const checkInDate = document.querySelector('input[name="check_in_date"]').value;
            const checkOutDate = document.querySelector('input[name="check_out_date"]').value;

            // Hitung jumlah malam
            const nights = calculateNights(checkInDate, checkOutDate);
            const totalPrice = roomPrice * totalRooms * nights;

            // Update Ringkasan Reservasi
            document.getElementById('room-type-name').textContent = roomTypeName;
            document.getElementById('room-details').textContent = `${totalRooms} kamar x ${nights} malam`;
            document.getElementById('room-price').textContent = `IDR ${totalPrice.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })}`;

            // Pastikan nilai jumlah kamar dikirim dalam form
            document.getElementById('hidden_total_room').value = totalRooms;

            // Tampilkan Ringkasan Reservasi
            document.getElementById('order-summary').classList.remove('hidden');
        });
    });


    // Submit reservation form
    document.getElementById('reservation-form').addEventListener('submit', function (event) {
        event.preventDefault();

        if (!roomTypeId) {
            alert("Pilih tipe kamar terlebih dahulu.");
            return;
        }

        const formData = new FormData(this);
        formData.append('room_type_id', roomTypeId);

        console.log([...formData.entries()]); // Cek apakah total_room terkirim

        // Tambahkan token CSRF jika diperlukan
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);

        fetch(this.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = document.getElementById('reservation-modal');

                // Tampilkan modal
                modal.classList.remove('hidden');

                // Isi data ke dalam modal
                const reservation = data.data;
                document.querySelector('#reservation-modal .reservation-id').textContent = reservation.id;
                document.querySelector('#reservation-modal .guest-name').textContent = reservation.user.full_name;
                document.querySelector('#reservation-modal .guest-email').textContent = reservation.user.email;
                document.querySelector('#reservation-modal .check-in-date').textContent = reservation.check_in_date;
                document.querySelector('#reservation-modal .check-out-date').textContent = reservation.check_out_date;
                document.querySelector('#reservation-modal .total-room').textContent = `${reservation.total_room} Kamar`;
                document.querySelector('#reservation-modal .total-guest').textContent = `${reservation.total_guest} Tamu`;
                document.querySelector('#reservation-modal .room-type').textContent = reservation.room_type.tipe_kamar;
                document.querySelector('#reservation-modal .total-price').textContent = `IDR ${reservation.total_price}`;
            } else {
                alert('Gagal membuat reservasi');
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error);
        });

    });


    document.querySelector('#cancel-create-reservation-btn button').addEventListener('click', () => {
        Swal.fire({
            title: "Anda ingin membatalkan membuat reservasi?",
            text: "Dengan menutup konfirmasi ini, Anda akan membatalkan reservasi yang dibuat.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Batalkan",
            cancelButtonText: "Tidak"
        }).then((result) => {
            if (result.isConfirmed) {
                // Ambil ID reservasi dari modal
                const reservationId = document.querySelector('#reservation-modal .reservation-id').textContent;

                // Kirim permintaan untuk menghapus reservasi
                fetch(`/cancel-create-reservation/${reservationId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Dibatalkan!", "Membuat Reservasi telah dibatalkan.", "success");
                            // Sembunyikan modal
                            document.getElementById('reservation-modal').classList.add('hidden');
                            
                        } else {
                            Swal.fire("Gagal!", "Reservasi Gagal dibatalkan.", "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Kesalahan!", "Terjadi kesalahan saat membatalkan reservasi.", "error");
                    });
            }
        });
    });


    // Hitung jumlah malam antara dua tanggal
    function calculateNights(checkInDate, checkOutDate) {
        const startDate = new Date(checkInDate);
        const endDate = new Date(checkOutDate);
        const differenceInTime = endDate - startDate;
        return differenceInTime / (1000 * 3600 * 24);
    }

    // Inisialisasi flatpickr
    flatpickr("#check-in", {
        minDate: "today",
        dateFormat: "d M Y",
    });

    flatpickr("#check-out", {
        minDate: "today",
        dateFormat: "d M Y",
    });


    document.querySelector('.cash-button').addEventListener('click', () => {
        const reservationId = document.querySelector('#reservation-modal .reservation-id').textContent;

        fetch('/process-cash-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ reservation_id: reservationId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Pembayaran Cash Berhasil!',
                    text: 'Terima kasih telah melakukan pembayaran.',
                    imageUrl: '/assets/card-payment.png',
                    imageWidth: 200,
                    imageHeight: 200,
                    imageAlt: 'Pembayaran',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    background: '#f8f9fa',
                    customClass: {
                        title: 'text-xl', // Gunakan kelas Tailwind untuk ukuran teks
                        text: 'text-base', // Gunakan kelas Tailwind untuk ukuran teks
                        popup: 'p-4', // (opsional) tambahkan padding pada popup jika diperlukan
                    },
                }).then(() => {
                    location.reload(); // Reload halaman setelah popup ditutup
                });

            } else {
                Swal.fire({
                    title: 'Gagal Memproses Pembayaran',
                    text: 'Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33',
                });
            }
        });
    });


    document.querySelector('.digital-button').addEventListener('click', () => {
        const reservationId = document.querySelector('#reservation-modal .reservation-id').textContent;

        fetch('/get-snap-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ reservation_id: reservationId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.snapToken) {
                // Tampilkan Snap Midtrans popup
                snap.pay(data.snapToken, {
                    onSuccess: function (result) {
                        // Kirim data ke server untuk memperbarui status pembayaran
                        fetch("{{ route('payments.updateStatus') }}", {
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
                                    title: data.message || 'Pembayaran Online Berhasil!',
                                    text: 'Terima kasih telah melakukan pembayaran.',
                                    imageUrl: '/assets/card-payment.png',
                                    imageWidth: 200,
                                    imageHeight: 200,
                                    imageAlt: 'Pembayaran',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3085d6',
                                    background: '#f8f9fa',
                                    customClass: {
                                        title: 'text-xl', // Gunakan kelas Tailwind untuk ukuran teks
                                        text: 'text-base', // Gunakan kelas Tailwind untuk ukuran teks
                                    },
                                }).then(() => {
                                    location.reload(); // Reload halaman setelah popup ditutup
                                });
                            } else {
                                throw new Error(data.message || 'Gagal memperbarui status pembayaran.');
                            }
                        })
                        .catch(error => {
                            console.error('Kesalahan:', error.message);
                            Swal.fire({
                                title: 'Kesalahan!',
                                text: 'Terjadi kesalahan: ' + error.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#d33',
                            });
                        });
                    },


                    onPending: function(result) {
                        alert('Menunggu pembayaran selesai.');
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal.');
                    },
                });
            } else {
                alert('Gagal mendapatkan Snap Token.');
            }
        });
    });


</script>


@endsection        
