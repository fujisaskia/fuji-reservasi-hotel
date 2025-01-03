<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')

<div class="max-w-5xl border bg-white rounded-t-3xl mx-auto p-6 text-xs">
    
    <!-- Image Gallery -->
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Main Image -->
        <div class="flex space-x-2 w-full">
            @php
            $fotos = json_decode($roomType->foto); // Mendecode JSON path foto
            @endphp
            @if ($fotos)
                @foreach($fotos as $foto)
                    <img src="{{ asset('storage/' . $foto) }}" alt="Room Image" class="rounded-lg w-1/2 h-60 object-cover">
                @endforeach
            @else
                <img src="https://via.placeholder.com/400x250" alt="Room Image" class="rounded-lg w-1/2 h-60 object-cover">
            @endif
            <img src="{{ asset ('assets/kitchen.jpg') }}" alt="Room Image" class="rounded-lg w-1/2 h-60 object-cover">
        </div>
    </div>

    <!-- Room Info and Price -->
    <div class="flex flex-col md:flex-row mt-6 gap-4">
        <!-- Room Info -->
        <div class="flex-1 space-y-2">
            <div class="pb-5 border-b border-gray-300">
                <div class="mb-2">
                    <h2 class="text-xl font-semibold">{{ $roomType->tipe_kamar }}</h2>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-3xl font-semibold">IDR <span class="text-rose-800">{{ number_format($roomType->harga, 0, ',', ',') }}</span></h2>
                        <span class="text-xs font-medium">/kamar/malam</span>
                    </div>
                    
                </div>
                <div class="space-y-1 text-gray-600">
                    <div class="flex space-x-2 items-center">
                        <i class="fa-regular fa-user"></i>
                        <p>{{ $roomType->kapasitas }}<span> Dewasa</span></p>
                    </div>
                    <p class="flex space-x-2 text-[11px] items-center text-red-800 italic">
                        {{-- <i class="fa-solid fa-circle-info"></i> --}}
                        @if ($roomType->available_rooms_count > 0)
                            {{-- <span class="">{{ $roomType->available_rooms_count }} kamar terakhir kami</span> --}}
                        @else
                            <span class="">Maaf, kamar saat ini tidak tersedia</span>
                        @endif
                    </p>                    
                </div>
            </div>

            {{-- Kebijakan --}}
            <div class="space-y-2 py-5 border-b  border-gray-300">
                <h4 class="font-semibold text-base">Kebijakan  :</h4>
                <div class="flex space-x-2 items-start px-2">
                    <i class="fa-solid fa-circle-info text-rose-700"></i>
                    <p>Pemesanan hanya dapat dilakukan secara online. Semua pemesanan bersifat final, Pembatalan pemesanan harus dilakukan minimal 24 jam sebelum tanggal check-in dan pembatalan <span class="underline">tidak akan mendapatkan pengembalian dana </span>dalam kondisi apa pun.</p>
                </div>
            </div>

            <div class="space-y-5 py-5">

                {{-- deskripsi --}}
                <div class="space-y-2">
                    <h4 class="font-semibold text-base">Deskripsi Kamar :</h4>
                    <p>{{ $roomType->deskripsi_kamar }}</p>
                </div>
                {{-- fasilitas kamar --}}
                <div class="space-y-2">
                    <h4 class="font-semibold text-base">Fasilitas :</h4>
                    <ul class="leading-loose">
                        @foreach ($fasilitasArray as $fasilitas)
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-check text-rose-800"></i>
                                <span>{{ trim($fasilitas) }}</span> <!-- trim untuk menghapus spasi di awal/akhir -->
                            </li>
                        @endforeach
                    </ul>
                    
                </div>
            </div>
        </div>
        
<!-- Price and Booking Card -->
<form action="{{ route('reservations.store', ['roomTypeId' => $roomType->id]) }}" method="POST" class="bg-white shadow-lg hover:shadow-rose-200 rounded-lg p-4 border-y-2 border-rose-200 sticky top-20 duration-500 {{ $roomType->available_rooms_count == 0 ? 'bg-gray-100 disabled' : '' }} inline-block max-h-max">
    @csrf
    <div class="flex justify-center pb-3 border-b">
        <span class="text-lg font-semibold">Pemesanan</span>
    </div>

    @if(session('error'))
    <div class="bg-red-500 text-white p-4 mb-4 rounded-md">
        {{ session('error') }}
    </div>
    @endif

    {{-- Input tanggal --}}
    <div class="flex mt-3 space-x-2">
        <div class="w-full">
            <label for="checkin" class="block text-[10px] font-semibold uppercase">Check-in</label>
            <input 
                {{-- type="date"  --}}
                id="checkin"
                placeholder="Pilih tgl. Check-In"
                name="check_in_date" 
                required 
                class="w-full block border border-gray-200 rounded-lg p-2 focus:outline-none focus:ring focus:ring-yellow-100 mt-1" 
                {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
        </div>
        <div class="w-full">
            <label for="checkout" class="block text-[10px] font-semibold uppercase">Check-out</label>
            <input 
                {{-- type="date"  --}}
                id="checkout" 
                placeholder="Pilih tgl. Check-Out"
                name="check_out_date" 
                required 
                class="w-full block border border-gray-200 rounded-lg p-2 focus:outline-none focus:ring focus:ring-yellow-100 mt-1" 
                {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
        </div>
    </div>

    {{-- Quantity --}}
    <div class="flex p-2 mt-4 justify-between space-x-4">
        {{-- Quantity room --}}
        <div class="w-full">
            <label for="room_quantity" class="block text-[10px] mb-2 uppercase font-semibold">Jumlah Kamar</label>
            <div class="flex items-center">
                <button 
                    type="button" 
                    id="decrement-room" 
                    class="p-2 bg-white border border-gray-500 hover:bg-gray-200 rounded-full"
                    {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-minus fa-sm"></i>
                </button>
                <input 
                    type="number" 
                    id="room_quantity" 
                    name="total_room" 
                    value="1" 
                    min="1" 
                    max="{{ $roomType->available_rooms_count }}" 
                    class="text-center w-12 mx-2 rounded-md" 
                    {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
                <button 
                    type="button" 
                    id="increment-room" 
                    class="p-2 bg-white border border-gray-500 hover:bg-gray-200 rounded-full"
                    {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-plus fa-sm"></i>
                </button>
            </div>
            <p id="quantity-message-room" class="text-[10px] italic text-red-600 mt-2 hidden">Kuantitas kamar sudah tercapai</p>
        </div>
        
        {{-- Quantity guest --}}
        <div class="w-full">
            <label for="guest_quantity" class="block text-[10px] mb-2 uppercase font-semibold">Jumlah Tamu</label>
            <div class="flex items-center">
                <button 
                    type="button" 
                    id="decrement-guest" 
                    class="p-2 bg-white border border-gray-500 hover:bg-gray-200 rounded-full"
                    {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-minus fa-sm"></i>
                </button>
                <input 
                    type="number" 
                    id="guest_quantity" 
                    name="total_guest" 
                    value="1" 
                    min="1" 
                    class="text-center w-12 mx-2 rounded-md"
                    {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
                <button 
                    type="button" 
                    id="increment-guest" 
                    class="p-2 bg-white border border-gray-500 hover:bg-gray-200 rounded-full"
                    {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-plus fa-sm"></i>
                </button>
            </div>
            <p id="quantity-message-guest" class="text-[10px] italic text-red-600 mt-2 hidden">Kuantitas tamu sudah tercapai</p>
        </div>
    </div>

    {{-- Total harga --}}
    <div class="flex justify-between mt-4 bg-gray-100 py-3 px-2 rounded">
        <div class="flex flex-col">
            <span class="text-xs">IDR {{ number_format($roomType->harga, 0, ',', ',') }}</span>
            <span class="text-[11px] text-gray-600" id="price-details">1 kamar x 1 malam</span>
        </div>
        <span id="total-price" class="font-semibold text-gray-900">IDR {{ number_format($roomType->harga, 0, ',', ',') }}</span>
    </div>

    {{-- Button Booking --}}
    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-400 text-white p-3 mt-6 rounded-md font-semibold focus:scale-95 duration-300" {{ $roomType->available_rooms_count == 0 ? 'disabled' : '' }}>Booking</button>
</form>


    </div>
</div>


<script>
       document.addEventListener('DOMContentLoaded', function () {
        const roomPrice = {{ $roomType->harga }}; // Harga per kamar per malam (dalam IDR)
        const maxRooms = {{ $roomType->available_rooms_count }};
        
        let checkInDate = null;
        let checkOutDate = null;

        // Elemen DOM
        const roomQuantityInput = document.getElementById('room_quantity');
        const guestQuantityInput = document.getElementById('guest_quantity');
        const checkInInput = document.getElementById('checkin');
        const checkOutInput = document.getElementById('checkout');
        const totalPriceElement = document.getElementById('total-price');
        const priceDetailsElement = document.getElementById('price-details');

        // Menghitung total harga
        function calculateTotalPrice() {
            if (checkInDate && checkOutDate) {
                const nights = (checkOutDate - checkInDate) / (1000 * 60 * 60 * 24);
                const roomQuantity = parseInt(roomQuantityInput.value);
                const totalPrice = roomPrice * roomQuantity * nights;
                
                priceDetailsElement.textContent = `${roomQuantity} kamar x ${nights} malam`;
                totalPriceElement.textContent = `IDR ${totalPrice.toLocaleString()}`;
            }
        }

        // Inisialisasi Flatpickr untuk Check-In
        flatpickr("#checkin", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function (selectedDates) {
                checkInDate = selectedDates[0];
                calculateTotalPrice();
            },
        });

        // Inisialisasi Flatpickr untuk Check-Out
        flatpickr("#checkout", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function (selectedDates) {
                checkOutDate = selectedDates[0];
                calculateTotalPrice();
            },
        });

        // Perubahan jumlah kamar
        roomQuantityInput.addEventListener('input', calculateTotalPrice);
        guestQuantityInput.addEventListener('input', calculateTotalPrice);

        // Fungsi untuk mengurangi jumlah kamar
        document.getElementById('decrement-room').addEventListener('click', () => {
            let quantity = parseInt(roomQuantityInput.value);
            if (quantity > 1) {
                roomQuantityInput.value = quantity - 1;
                calculateTotalPrice();
            }
        });

        // Fungsi untuk menambah jumlah kamar
        document.getElementById('increment-room').addEventListener('click', () => {
            let quantity = parseInt(roomQuantityInput.value);
            if (quantity < maxRooms) {
                roomQuantityInput.value = quantity + 1;
                calculateTotalPrice();
            }
        });

        // Fungsi untuk mengurangi jumlah tamu
        document.getElementById('decrement-guest').addEventListener('click', () => {
            let quantity = parseInt(guestQuantityInput.value);
            if (quantity > 1) {
                guestQuantityInput.value = quantity - 1;
            }
        });

        // Fungsi untuk menambah jumlah tamu
        document.getElementById('increment-guest').addEventListener('click', () => {
            let quantity = parseInt(guestQuantityInput.value);
            guestQuantityInput.value = quantity + 1;
        });
    });

</script>

@endsection