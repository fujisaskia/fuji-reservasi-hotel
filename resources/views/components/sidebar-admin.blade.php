    <!-- Navbar -->
    <header class="nav fixed top-0 left-0 right-0 bg-white shadow-sm p-3">
        <div class="flex justify-between items-center md:ml-56">
            <div class="flex items-center space-x-4">
                <button class="flex lg:hidden rounded-lg p-1 text-slate-900 ml-3 lg:ml-0 active:bg-white focus:outline-none focus:ring focus:ring-rose-300" id="menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>                  
                </button>
                {{-- <h3 class="text-md text-center items-center justify-center font-bold ">@yield('title')</h3>             --}}
            </div>

            <!-- Profile Icon with Dropdown using Alpine.js -->
            <div x-data="{ openProfile: false }" class="relative">
                <a href="#" @click="openProfile = !openProfile" class="flex items-center ml-auto space-x-2">
                    <p class="text-sm md:text-md">{{ Auth::user()->full_name }}</p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 rounded-full text-gray-400 border-gray-300">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                    </svg>
                </a>
                <!-- Dropdown Menu -->
                <div x-show="openProfile" @click.away="openProfile = false" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95" 
                    x-transition:enter-end="opacity-100 transform scale-100" 
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100" 
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white border-2 border-gray-100 rounded-lg shadow-lg">
                    <a href="{{ route('edit.profile', ['id' => Auth::guard('admin')->id()]) }}" class="flex items-center space-x-2 font-semibold px-4 py-3 text-sm lg:text-xs text-gray-700 hover:bg-gray-100">
                        <i class="fa-regular fa-user"></i>
                        <p>Profile</p>
                    </a>
                    <div x-data="{ showModal: false }" class="">
                        <button @click="showModal = true" class="flex w-full items-center space-x-2 font-semibold px-4 py-3 text-sm lg:text-xs text-rose-600 hover:text-white  hover:bg-rose-600 focus:bg-rose-500 rounded-b-lg duration-300">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <p>Keluar</p>
                        </button>

                        <!-- Popup Konfirmasi -->
                        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center text-xs bg-gray-900 bg-opacity-50 z-50 px-4">
                            <div class="bg-white w-full md:w-1/3 p-6 rounded shadow-lg">
                                <h2 class="text-lg font-bold mb-4">Konfirmasi Logout</h2>
                                <p class="mb-6">Apakah Anda yakin ingin keluar?</p>
                                <div class="flex justify-end space-x-4">
                                    <button 
                                        @click="showModal = false" 
                                        class="bg-gray-300 px-4 py-2 rounded"
                                    >
                                        Batal
                                    </button>
                                    <form action="{{ route('logout.admin') }}" method="GET">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- Sidebar and Content Wrapper -->
    <div class="flex"> <!-- Added padding top to account for the navbar height -->
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed w-64 lg:w-60 h-screen bg-white py-4 lg:py-4 px-2 shadow-lg z-10 transform -translate-x-full transition-transform duration-300 md:translate-x-0 md:block">
            <button id="close-button" class="block md:hidden mt-2 ml-auto border-2 border-blue-200 rounded-lg p-1 active:bg-white focus:outline-none focus:ring focus:ring-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="space-y-2 py-8 items-center text-center bg-white rounded-lg">
                <img src="{{ $hotelSetting->logo_path ? asset('storage/' . $hotelSetting->logo_path) : asset('assets/default-logo.png') }}" alt="{{ $hotelSetting->name ?? 'Default Logo' }}" class="w-24 mx-auto"> 
                <span class="font-semibold text-lg text-gray-800 font-playfair"><span class="text-rose-800">{{ $hotelSetting->name ?? 'Hotel' }}</span> Hotel</span>
            </div>
        

            <ul class="">
                <a href="/dashboard/admin">
                    <li class="flex items-center space-x-2 text-rose-800 py-3 px-4 {{ Request::is('dashboard/admin') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100 hover:text-red-800 ' }} group">
                        <i class="fa-solid fa-hotel"></i>
                        <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Dashboard</p>
                    </li>
                </a>

                <a href="/admin/reservations" class="">
                    <li class="flex items-center space-x-2 text-rose-800 py-3 px-4 mt-1 {{ Request::is('admin.reservations') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100 hover:text-red-800 ' }} group">
                        <i class="fa-solid fa-tags"></i>
                        <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Manajemen Reservasi</p>
                    </li>
                </a>
                
                <div x-data="{ openRooms: false }" x-init="openRooms = window.location.pathname.includes('/room-manage/admin') || window.location.pathname.includes('/room-type/admin')">
                    <!-- Trigger untuk dropdown -->
                    <a href="javascript:void(0)" @click="openRooms = !openRooms">
                        <li class="flex items-center  text-rose-800 hover:bg-slate-100 py-3 px-4 hover:text-red-800 mt-2 group">
                            <div class="flex space-x-2">
                                <i class="fa-solid fa-bed"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Manajemen Kamar</p>
                            </div>
                            <i :class="openRooms ? 'fa-solid fa-caret-up' : 'fa-solid fa-caret-down'" class="ml-auto"></i>
                        </li>
                    </a>
                
                    <!-- Dropdown dengan animasi transisi smooth -->
                    <div x-show="openRooms" @click.away="openRooms = false" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95" 
                    x-transition:enter-end="opacity-100 transform scale-100" 
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100" 
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="bg-white mx-3 mt-1">
                    <a href="/room-manage/admin">
                        <li class="flex items-center space-x-2 py-3 px-4 {{ Request::is('room-manage/admin') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100  text-red-800' }} group">
                            <i class="fa-solid fa-bed fa-sm"></i>
                            <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Kamar</p>
                        </li>
                    </a>
                    
                    <a href="/room-type/admin">
                        <li class="flex items-center space-x-2 py-3 px-4 {{ Request::is('room-type/admin') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100  text-red-800' }} group">
                            <i class="fa-solid fa-door-open"></i>
                            <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Tipe Kamar</p>
                        </li>
                    </a>                    
                    </div>
                </div>
                
                <div x-data="{ openService: false }" x-init="openService = window.location.pathname.includes('/service/admin') || window.location.pathname.includes('/service-category')">
                    <!-- Trigger untuk dropdown -->
                    <a href="javascript:void(0)" @click="openService = !openService">
                        <li class="flex items-center  text-rose-800 hover:bg-slate-100 py-3 px-4 hover:text-red-800 mt-2 group">
                            <div class="flex space-x-2">
                                <i class="fa-solid fa-bell-concierge"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Manajemen Layanan</p>
                            </div>
                            <i :class="openService ? 'fa-solid fa-caret-up' : 'fa-solid fa-caret-down'" class="ml-auto"></i>
                        </li>
                    </a>
                    
                    <!-- Dropdown dengan animasi transisi smooth -->
                    <div x-show="openService" @click.away="openService = false" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95" 
                    x-transition:enter-end="opacity-100 transform scale-100" 
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100" 
                    x-transition:leave-end="opacity-0 transform scale-95"
                         class="bg-white mx-3 mt-1">
                         <a href="/service/admin">
                            <li class="flex items-center space-x-2 py-3 px-4 {{ Request::is('service/admin') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100  text-red-800' }} group">
                                <i class="fa-solid fa-bell-concierge"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Layanan</p>
                            </li>
                        </a>
                        
                        <a href="/service-category">
                            <li class="flex items-center space-x-2 py-3 px-4 {{ Request::is('service-category') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100  text-red-800' }} group">
                                <i class="fa-solid fa-list"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Kategori Layanan</p>
                            </li>
                        </a>                    
                    </div>
                </div>
                
                <div x-data="{ open: false }" x-init="open = window.location.pathname.includes('/admin/guest') || window.location.pathname.includes('/users')">
                    <!-- Trigger untuk dropdown -->
                    <a href="javascript:void(0)" @click="open = !open">
                        <li class="flex items-center  text-rose-800 hover:bg-slate-100 py-3 px-4 hover:text-red-800 mt-2 group">
                            <div class="flex space-x-2">
                                <i class="fa-solid fa-users-gear"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Manajemen Pengguna</p>
                            </div>
                            <i :class="open ? 'fa-solid fa-caret-up' : 'fa-solid fa-caret-down'" class="ml-auto"></i>
                        </li>
                    </a>
                    
                    <!-- Dropdown dengan animasi transisi smooth -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95" 
                    x-transition:enter-end="opacity-100 transform scale-100" 
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100" 
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="bg-white mx-3 mt-1">
                        <a href="/admin/guest">
                            <li class="flex items-center space-x-2 py-3 px-4 {{ Request::is('admin/guest') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100  text-red-800' }} group">
                                <i class="fa-solid fa-couch fa-sm"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Tamu</p>
                            </li>
                        </a>
                        
                        <a href="/users">
                            <li class="flex items-center space-x-2 py-3 px-4 {{ Request::is('users') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100  text-red-800' }} group">
                                <i class="fa-solid fa-user-tag fa-sm"></i>
                                <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Users</p>
                            </li>
                        </a>                    
                    </div>

                </div>

                <a href="/admin/ulasan">
                    <li class="flex items-center space-x-2 text-rose-800 hover:bg-slate-100 py-3 px-4 hover:text-red-800 mt-1 group">
                        <i class="fa-regular fa-comment"></i>
                        <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Manajemen Ulasan</p>
                    </li>
                </a>

                <a href="/hotel-settings" class="">
                    <li class="flex items-center space-x-2 text-rose-800 py-3 px-4 mt-3  border-t {{ Request::is('hotel-settings') ? 'border-l-4 border-rose-700 text-rose-900 bg-gray-100' : 'hover:bg-slate-100 hover:text-red-800 ' }} group">
                        <i class="fa-solid fa-sliders"></i>
                        <p class="text-sm md:text-xs font-medium group-hover:translate-x-1 duration-500">Settings</p>
                    </li>
                </a>
   
                 
            </ul>
        
        </aside>

    </div>


    <!-- Modal Popup Konfirmasi Logout  -->
    <div id="confirmationLogout" class="fixed inset-0 z-50 items-center justify-center hidden bg-gray-800 bg-opacity-50 text-sm md:text-xs">
        <div class="bg-white py-6 px-12 rounded-lg shadow-lg">
            <p class="text-center text-gray-700">Yakin mau logout?</p>
            <div class="mt-4 flex justify-center gap-6">
                <button id="cancelLogout" class="bg-white text-green-500 border border-green-500 py-2 px-4 hover:bg-green-500 hover:text-white">Tidak</button>
                <button id="confirmLogout" class="bg-white border border-red-500 text-red-500 py-2 px-4 hover:bg-red-500 hover:text-white">Ya</button>
            </div>
        </div>
    </div>

    <!-- JavaScript sidebar-->
    <script>
                // sidebar //

        // Ambil elemen-elemen yang diperlukan
        const menuButton = document.getElementById('menu-button');
        const closeButton = document.getElementById('close-button');
        const sidebar = document.getElementById('sidebar');

        // Fungsi untuk menampilkan sidebar
        menuButton.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
        });

        // Fungsi untuk menyembunyikan sidebar
        closeButton.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });


            // logout //

        // Menampilkan popup saat tombol logout diklik
        document.getElementById('logout').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('confirmationLogout').classList.remove('hidden');
        });

        // Mengonfirmasi logout
        document.getElementById('confirmLogout').addEventListener('click', function() {
            // Aksi penghapusan di sini (misalnya, mengirim request penghapusan ke server. kalau mau pakai respon server buka komentar code dibawah ini aja)
            // alert('Item dihapus');
            document.getElementById('confirmationLogout').classList.add('hidden');

            // Mengarahkan pengguna ke halaman landing page
            window.location.href = '/';
        });

        // Membatalkan logout
        document.getElementById('cancelLogout').addEventListener('click', function() {
            document.getElementById('confirmationLogout').classList.add('hidden');
        });
    </script>