<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')

<div class="max-w-6xl mx-auto text-sm lg:text-xs">
    <div class="flex gap-4">
        <!-- Room List Section -->
        <div class="flex-1 space-y-4 px-2 lg:px-0">
            <div class="bg-white lg:flex-row items-center justify-between p-4 space-y-2 rounded-xl sticky top-4 border-b lg:border border-gray-200 shadow-md">
                <!-- Filter Options -->
                <div class="space-y-2 border-t md:border-none text-xs">
                    <label for="countries" class="">Gunakan Filter :</label>
                    {{-- kategori tipe kamar yang diambil dari tb room_types --}}
                    <select id="roomTypeFilter" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:border-yellow-300 block w-2/3 lg:w-1/3 p-2">
                        <option value="">Semua Tipe</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->tipe_kamar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="roomsSection" class="">
                {{-- relative --}}
                {{-- Rooms Section --}}
                @include('components.room-card')
                {{-- Room Section End --}}
            </div>

        </div>

    </div>
</div>
  

{{-- detail room end--}}

<script>
    //kategory filter room
    document.getElementById('roomTypeFilter').addEventListener('change', function() {
        var selectedType = this.value;
        var roomCards = document.querySelectorAll('.room-card');
        
        roomCards.forEach(function(card) {
            if (selectedType === "" || card.getAttribute('data-room-type') === selectedType) {
                card.style.display = 'flex'; // Tampilkan room card jika sesuai kategori
            } else {
                card.style.display = 'none'; // Sembunyikan room card jika tidak sesuai kategori
            }
        });
    });


    // kustomisasi tanggal
    document.addEventListener('DOMContentLoaded', function () {
        let checkInDate = null;
        let checkOutDate = null;
        const searchButton = document.getElementById('searchButton');
        const roomsSection = document.getElementById('roomsSection');

        // Inisialisasi Flatpickr untuk Check-In
        flatpickr("#check-in", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d", // Format yang dikirim ke server
            minDate: "today", // Mulai dari hari ini
            onChange: function (selectedDates) {
                checkInDate = selectedDates[0];
                calculateNights();
            },
        });

        // Inisialisasi Flatpickr untuk Check-Out
        flatpickr("#check-out", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d", // Format yang dikirim ke server
            minDate: "today", // Mulai dari hari ini
            onChange: function (selectedDates) {
                checkOutDate = selectedDates[0];
                calculateNights();
            },
        });

        // Fungsi untuk menghitung jumlah malam
        function calculateNights() {
            if (checkInDate && checkOutDate) {
                const timeDiff = checkOutDate - checkInDate;
                const nightsCount = Math.ceil(timeDiff / (1000 * 3600 * 24)); // Konversi ms ke hari

                // Update UI
                document.getElementById("number_of_night").textContent = `${nightsCount} nights`;

                // Validasi untuk input
                if (nightsCount <= 0) {
                    alert("Check-out date must be after Check-in date!");
                    document.getElementById("check-out").value = "";
                    document.getElementById("number_of_night").textContent = `0 nights`;
                }
            }
        }
        // searchButton.addEventListener('click', function () {
        //     // Tambahkan spinner overlay
        //     const spinnerOverlay = document.createElement('div');
        //     spinnerOverlay.className = 'spinner-overlay';
        //     spinnerOverlay.innerHTML = '<div class="spinner"></div>';
        //     roomsSection.appendChild(spinnerOverlay);

        //     // Simulasikan proses pencarian (delay)
        //     setTimeout(() => {
        //         // Hapus spinner setelah selesai
        //         roomsSection.removeChild(spinnerOverlay);
        //     }, 1000); // 2 detik
        // });
    });

    // document.addEventListener('DOMContentLoaded', function () {
    //     const updateButton = document.getElementById('updateButton');
    //     const roomsContainer = document.getElementById('roomsContainer');
    //     const nightsCountElement = document.getElementById('number_of_night');
    //     const checkInInput = document.getElementById('check-in');
    //     const checkOutInput = document.getElementById('check-out');

    //     function saveToLocalStorage() {
    //         const checkInDate = checkInInput.value;
    //         const checkOutDate = checkOutInput.value;
    //         const nightsCount = calculateNights();

    //         localStorage.setItem('checkInDate', checkInDate);
    //         localStorage.setItem('checkOutDate', checkOutDate);
    //         localStorage.setItem('nightsCount', nightsCount);
    //     }

    //     function loadFromLocalStorage() {
    //         const checkInDate = localStorage.getItem('checkInDate');
    //         const checkOutDate = localStorage.getItem('checkOutDate');
    //         const nightsCount = localStorage.getItem('nightsCount');

    //         if (checkInDate && checkOutDate && nightsCount) {
    //             checkInInput.value = checkInDate;
    //             checkOutInput.value = checkOutDate;
    //             nightsCountElement.textContent = `${nightsCount} nights`;

    //             updatePrices();
    //             updateRoomNights(nightsCount);
    //         }
    //     }

    //     function calculateNights() {
    //         const checkInDate = new Date(checkInInput.value);
    //         const checkOutDate = new Date(checkOutInput.value);

    //         if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
    //             const timeDiff = Math.abs(checkOutDate - checkInDate);
    //             return Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
    //         }
    //         return 0;
    //     }

    //     function updatePrices() {
    //         const nightsCount = calculateNights();

    //         roomsContainer.querySelectorAll('.room-card').forEach(roomCard => {
    //             const priceElement = roomCard.querySelector('[id^="roomPrice"]');
    //             const basePrice = parseInt(priceElement.getAttribute('data-base-price'));

    //             const newPrice = basePrice * nightsCount;
    //             priceElement.textContent = newPrice.toLocaleString('id-ID');
    //         });
    //     }

    //     function updateRoomNights(nightsCount) {
    //         roomsContainer.querySelectorAll('.room-card').forEach(roomCard => {
    //             const nightsDisplay = roomCard.querySelector('#displayNights');
    //             nightsDisplay.textContent = nightsCount;
    //         });
    //     }

    //     // Update hidden inputs sebelum form disubmit
    //     function updateHiddenInputs() {
    //         roomsContainer.querySelectorAll('.room-card').forEach(roomCard => {
    //             const nightsCount = calculateNights();
    //             const roomId = roomCard.getAttribute('data-room-type');

    //             const checkInDate = checkInInput.value;
    //             const checkOutDate = checkOutInput.value;

    //             document.getElementById(`check_in_date_${roomId}`).value = checkInDate;
    //             document.getElementById(`check_out_date_${roomId}`).value = checkOutDate;
    //             document.getElementById(`nights_count_${roomId}`).value = nightsCount;
    //         });
    //     }

    //     updateButton.addEventListener('click', function (event) {
    //         event.preventDefault();
    //         const nightsCount = calculateNights();

    //         saveToLocalStorage();
    //         nightsCountElement.textContent = `${nightsCount} nights`;
    //         updatePrices();
    //         updateRoomNights(nightsCount);
    //         updateHiddenInputs(); // Update hidden inputs sebelum form disubmit
    //     });

    //     // loadFromLocalStorage();
    // });

</script>



@endsection
