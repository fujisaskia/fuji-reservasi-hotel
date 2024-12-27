<nav class="sticky top-0 bg-gray-50 shadow-md py-3 text-xs z-10">
  <div class="max-w-6xl mx-4 lg:mx-auto flex justify-between items-center py-2">
    <!-- Logo -->
    <div class="flex items-center space-x-2">
      <img src="{{ $hotelSetting->logo_path ? asset('storage/' . $hotelSetting->logo_path) : asset('assets/default-logo.png') }}" 
      alt="{{ $hotelSetting->name ?? 'Default Logo' }}" 
      class="w-10 mx-auto">
      <span class="font-semibold text-lg text-gray-800 font-playfair"><span class="text-rose-800">{{ $hotelSetting->name ?? 'Hotel' }}</span> Hotel</span>
    </div>

    <button id="sidebar-toggle" class="flex lg:hidden">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-8 text-rose-800 hover:text-rose-600">
        <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75Zm7 10.5a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1-.75-.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Z" clip-rule="evenodd" />
      </svg>
    </button>
    
    <!-- Menu Items -->
      <div class="hidden lg:flex space-x-6">
        <a href="#home" class="p-2 hover:text-rose-900 hover:border-b hover:border-rose-900 hover:-translate-y-0.5 focus:scale-95 duration-300">Home</a>
        <a href="#about" class="p-2 hover:text-rose-900 hover:border-b hover:border-rose-900 hover:-translate-y-0.5 focus:scale-95 duration-300">Tentang</a>
        <a href="/rooms" class="p-2 hover:text-rose-900 hover:border-b hover:border-rose-900 hover:-translate-y-0.5 focus:scale-95 duration-300">Kamar</a>
        <a href="#testimonials" class="p-2 hover:text-rose-900 hover:border-b hover:border-rose-900 hover:-translate-y-0.5 focus:scale-95 duration-300">Testimoni</a>
        <a href="#contact" class="p-2 hover:text-rose-900 hover:border-b hover:border-rose-900 hover:-translate-y-0.5 focus:scale-95 duration-300">Kontak</a>
      </div>

    <!-- Book Now Button -->
    <div class="hidden lg:flex items-center space-x-3">
      <a href="/login" class="flex space-x-1 items-center font-semibold py-2 px-8 text-white bg-rose-700 hover:bg-rose-800 hover:-translate-x-1 focus:scale-95 rounded-l-xl duration-300">
          <i class="fa-solid fa-user-tag"></i>
          <span>Masuk</span>
      </a>

    </div>     
  </div>
</nav>


<!-- Sidebar -->
<div id="sidebar" class="hidden fixed inset-0 z-40 flex items-center justify-end bg-gray-800 bg-opacity-75 lg:hidden">
  <div class="bg-white w-64 h-full py-12 px-5 relative">
      <!-- Tombol Close -->
      <button id="close-sidebar" class="absolute top-4 right-4 text-rose-800 focus:text-rose-600 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
      </button>
      <!-- Menu Items -->
      <div class="space-y-4 mt-10">
          <a href="#" class="block p-3 focus:bg-rose-700 focus:text-white rounded-lg ">Beranda</a>
          <a href="#about" class="block p-3 focus:bg-rose-700 focus:text-white rounded-lg ">Tentang</a>
          <a href="#testimonials" class="block p-3 focus:bg-rose-700 focus:text-white rounded-lg ">Testimoni</a>
          <a href="#" class="block p-3 focus:bg-rose-700 focus:text-white rounded-lg ">Kontak</a>
      </div>
      <a href="/login" class="uppercase border-2 border-rose-700 font-semibold py-2 px-8 mt-8 items-center justify-center text-center mx-auto text-rose-900 hover:text-white bg-white hover:bg-rose-700 rounded-lg duration-300">
        Masuk
      </a>
  </div>
</div>

<div x-data="{ open: false }" class="lg:hidden">
  <!-- Button Fixed di Bawah -->
  {{-- <div class="fixed bottom-0 left-0 w-full z-30">
    <button @click="open = true" class="flex bg-rose-700 text-white font-bold py-4 rounded-t-md shadow-lg hover:bg-rose-800 transition duration-300 w-full justify-center text-center">
      Booking Now
    </button>
  </div> --}}

  <!-- Modal -->
  <div x-show="open" x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 translate-y-full"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-300"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 translate-y-full"
       class="fixed bottom-0 left-0 right-0 bg-white text-sm border border-gray-400 py-12 px-6 rounded-t-3xl shadow-lg z-30">
    
    <h2 class="text-lg font-semibold pb-4 border-b border-gray-300 text-center">Book Your Stay</h2>

    <div class="space-y-4 mt-5">
      <!-- Check-in field -->
      <div>
        <label for="checkin" class="block font-medium text-gray-700 uppercase">Check-in</label>
        <input type="date" id="checkin" class="w-full border border-gray-300 px-2 py-3 rounded-md focus:outline-none focus:ring focus:ring-yellow-500">
      </div>

      <!-- Check-out field -->
      <div>
        <label for="checkout" class="block font-medium text-gray-700 uppercase">Check-out</label>
        <input type="date" id="checkout" class="w-full border border-gray-300 px-2 py-3 rounded-md focus:outline-none focus:ring focus:ring-yellow-500">
      </div>

      <!-- Promo code field -->
      {{-- <div>
        <label for="promo" class="block font-medium text-gray-700 uppercase">Promo Code</label>
        <input type="text" id="promo" class="w-full border border-gray-300 px-2 py-3 rounded-md focus:outline-none focus:ring focus:ring-yellow-500" placeholder="Enter code">
      </div> --}}
    </div>

    <!-- Tombol Check Availability -->
    <div class="mt-6 block">
      <a href="/offers">
        <button class="w-full bg-yellow-500 text-white py-3 rounded-md hover:bg-white hover:text-yellow-700 focus:outline-none focus:ring focus:ring-yellow-600">Check Availability</button>
      </a>
    </div>

    <!-- Tombol Close -->
    <button @click="open = false" class="space-x-2 mt-4 text-gray-500 text-sm hover:text-gray-700 block mx-auto">
      <i class="fa-solid fa-xmark"></i>
      <span>Close</span>
    </button>
  </div>
</div>




<script>
  document.getElementById('sidebar-toggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('hidden');
  });

  document.getElementById('close-sidebar').addEventListener('click', function() {
      document.getElementById('sidebar').classList.add('hidden');
  });

  // JavaScript untuk toggle dropdown
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownForm');
    dropdown.classList.toggle('hidden');
  }

  // Menutup dropdown jika klik di luar area dropdown
  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownForm');
    const button = event.target.closest('button');

    // Hanya tutup dropdown jika area yang diklik bukan dropdown atau tombol
    if (!dropdown.contains(event.target) && !button) {
      dropdown.classList.add('hidden');
    }
  });

</script>

