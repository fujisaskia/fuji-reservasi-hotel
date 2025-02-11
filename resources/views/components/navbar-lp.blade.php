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

