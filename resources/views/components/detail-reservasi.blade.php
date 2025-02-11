<div x-show="openDetailReservasi" 
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto relative">
        <!-- Tombol untuk menutup modal -->
        <button @click="openDetailReservasi = false" class="absolute top-4 right-4 p-2 text-xl text-gray-500 hover:text-gray-700 rounded-full focus:ring focus:ring-blue-200">
            <i class="fa-solid fa-times"></i>
        </button>

        <!-- Konten Detail Reservasi -->
        <div class="mb-6 space-y-2">
            <h2 class="text-xl font-semibold text-center text-blue-900">Detail Reservasi</h2>
            <div class="w-1/3 h-1 bg-blue-900 mx-auto rounded-full"></div>
        </div>

        {{-- detail reservasi --}}
        <div class="">
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Nama Tamu:</span>
                <span class="text-rose-800">{{  $reservation->user->full_name  }}</span>
            </div>
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Invoice Number:</span>
                <span class="text-rose-800 font-mono text-base">
                    #{{ $reservation->invoice->invoice_number ?? 'Belum Ada Invoice' }}
                </span>
            </div>    
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Tipe Kamar:</span>
                <span class="text-rose-800">{{ $reservation->roomType->tipe_kamar }}</span>
            </div>
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Harga (IDR):</span>
                <span class="text-rose-800">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</span>
            </div>
            <div class="flex">
                <div class="flex flex-col w-full py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                    <span class="font-semibold">Jumlah Kamar:</span>
                    <span class="text-rose-800">{{ $reservation->total_room }} Kamar</span>
                </div>
                <div class="flex flex-col w-full py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                    <span class="font-semibold">Jumlah Tamu:</span>
                    <span class="text-rose-800">{{ $reservation->total_guest }} Tamu</span>
                </div>
            </div>
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Tgl. Reservasi:</span>
                <span class="text-rose-800">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</span>
            </div>
            <div class="flex">
                <div class="flex flex-col w-full py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                    <span class="font-semibold">Tgl. Check-In:</span>
                    <span class="text-rose-800">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</span>
                </div>
                <div class="flex flex-col w-full py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                    <span class="font-semibold">Tgl. Check-Out:</span>
                    <span class="text-rose-800">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Status Reservasi:</span>
                <span>
                    <p class="py-1 px-2 text-xs text-white rounded-full text-center italic
                        @if ($reservation->reservation_status === 'Pending') 
                            bg-yellow-50 text-yellow-700 border border-yellow-400
                        @elseif ($reservation->reservation_status === 'Confirmed') 
                            bg-green-50 text-green-700 border border-green-400
                        @elseif ($reservation->reservation_status === 'Checked-In') 
                            bg-blue-50 text-blue-700 border border-blue-400
                        @elseif ($reservation->reservation_status === 'Checked-Out') 
                            bg-rose-50 text-rose-700 border border-rose-400
                        @elseif ($reservation->reservation_status === 'Cancelled') 
                            bg-red-50 text-red-700 border border-red-400
                        @endif">
                        {{ $reservation->reservation_status }}
                    </p>
                </span>
            </div>
            <div class="flex justify-between py-3 px-2 border-b border-gray-300 hover:bg-gray-100">
                <span class="font-semibold">Status Bayar:</span>
                <span>
                    <p class="py-1 px-2 text-xs text-white rounded-full text-center italic
                        @if ($reservation->payment->payment_status === 'pending') 
                            bg-yellow-50 text-yellow-700 border border-yellow-400
                        @elseif ($reservation->payment->payment_status === 'success') 
                            bg-green-50 text-green-700 border border-green-400
                        @elseif ($reservation->payment->payment_status === 'failed') 
                            bg-red-50 text-red-700 border border-red-400
                        @endif">
                        {{ $reservation->payment->payment_status }}
                    </p>
                </span>
            </div>
        </div>
        <div class="flex justify-start mt-4">
            <button 
                onclick="confirmCancellation({{ $reservation->id }}, '{{ Auth::user()->role }}')"
                class="p-2 text-[11px] text-white rounded-r-md hover:translate-x-1 focus:ring-2 focus:ring-yellow-200 focus:scale-95 duration-300
                @if($reservation->reservation_status === 'Cancelled') bg-slate-500 cursor-not-allowed
                @else bg-rose-700 hover:bg-rose-800
                @endif"
                @if($reservation->reservation_status === 'Cancelled') disabled @endif
            >
                Batalkan reservasi
            </button>
        </div>        
        
    </div>
</div>

<script>
    function confirmCancellation(id, name) {
        Swal.fire({
            title: `<span class="text-base">${name}, anda ingin membatalkan reservasi ini?</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Kembali',
            customClass: {
                title: 'text-base'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/reservations/${id}/cancel`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Dibatalkan!', data.message, 'success');
                        // Optional: Refresh or update UI
                        location.reload();
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                });
            }
        });
    }
</script>