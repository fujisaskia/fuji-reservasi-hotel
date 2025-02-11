<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hotel Reservation')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
     {{-- alpinejs --}}
     <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
     {{-- Flatpickr date--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        /* Spinner overlay */
        .spinner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Spinner */
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ffc107; /* Warna kuning */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

    </style>


</head>
<body class="h-full font-poppins bg-slate-100">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-white shadow-lg p-4 text-xs font-semibold sticky top-0 z-10">
            <div class="max-w-6xl mx-auto flex justify-between items-center">
                <!-- Left: Logo or Title -->
                <a href="{{ url('/') }}" class="flex space-x-2 items-center text-lg tracking-wide md:text-xl font-bold text-gray-800">
                    <img src="{{ $hotelSetting->logo_path ? asset('storage/' . $hotelSetting->logo_path) : asset('assets/default-logo.png') }}" 
                    alt="{{ $hotelSetting->name ?? 'Default Logo' }}" class="w-8 h-auto">
                    <span class="font-semibold text-lg text-gray-800 font-playfair"><span class="text-rose-800">{{ $hotelSetting->name ?? 'Hotel' }}</span> Hotel</span>
                </a>
    
                <div class="relative md:inline-block text-left hidden">
                    <!-- Button -->
                    <button onclick="toggleDropdown()" class="flex space-x-2 items-center text-sm lg:text-xs text-rose-700 bg-gray-200 hover:bg-gray-300 py-2 px-3 rounded-full focus:scale-95 duration-300">
                        <i class="fa-regular fa-user"></i>
                        <p class="hidden md:flex">{{ Auth::user()->full_name }}</p>
                        <i class="flex md:hidden fa-solid fa-chevron-down"></i>
                    </button>
                
                    <!-- Dropdown menu -->
                    <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                        <ul class="py-1">
                            <li>
                                <a href="/profile" class="block px-4 py-3 text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 group">
                                    <i class="fa-solid fa-user mr-1 group-hover:scale-125 duration-300"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="/my-booking" class="block px-4 py-3 text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 group">
                                    <i class="fa-solid fa-tags mr-1 group-hover:scale-105 group-hover:-rotate-12 duration-300"></i>
                                    Booking-Ku
                                </a>
                            </li>
                            <li x-data="{ showModal: false }" class="">
                                <button @click="showModal = true" class="block w-full text-left px-4 py-3 border-t text-sm md:text-xs font-medium text-rose-800 hover:text-rose-900 hover:bg-gray-100 group">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-1 group-hover:scale-125 duration-300"></i>
                                    Logout
                                </button>

                                <!-- Popup Konfirmasi -->
                                <div x-show="showModal" class="fixed inset-0 flex items-center justify-center text-xs font-medium bg-gray-900 bg-opacity-50 z-50 px-4">
                                    <div class="bg-white w-full md:w-1/3 p-6 rounded shadow-lg">
                                        <h2 class="text-lg font-bold mb-4">Konfirmasi Logout</h2>
                                        <p class="mb-6">Apakah Anda yakin ingin keluar?</p>
                                        <div class="flex justify-end space-x-4">
                                            <button 
                                                @click="showModal = false" 
                                                class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded focus:scale-95 duration-300"
                                            >
                                                Batal
                                            </button>
                                            <form action="{{ route('logout') }}" method="GET">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded focus:scale-95 duration-300">
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    
                    
                </div>
                
                <div x-data="{ showSidebar: false }" class="relative md:hidden flex">
                    <!-- Tombol untuk membuka sidebar -->
                    <button @click="showSidebar = true" class="py-2 px-3 bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded-full text-gray-700 focus:scale-95 duration-300">
                        <i class="fa-regular fa-user text-lg"></i>
                    </button>
                
                    <!-- Sidebar -->
                    <div 
                        x-show="showSidebar" 
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full"
                        class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg z-50"
                    >
                        <!-- Header Sidebar -->
                        <div class="flex justify-end items-center p-4 border-b">
                            <h2 class="text-lg font-bold sr-only">Close Menu</h2>
                            <button @click="showSidebar = false" class="text-2xl text-gray-600 hover:text-gray-800">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                
                        <!-- Isi Menu -->
                        <ul class="py-12 px-4 space-y-2 text-sm md:text-xs">
                            <li>
                                <a href="/offers" class="flex px-4 py-3 items-center md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('user.offers') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mr-1">
                                        <path fill-rule="evenodd" d="M9 4.5a.75.75 0 0 1 .721.544l.813 2.846a3.75 3.75 0 0 0 2.576 2.576l2.846.813a.75.75 0 0 1 0 1.442l-2.846.813a3.75 3.75 0 0 0-2.576 2.576l-.813 2.846a.75.75 0 0 1-1.442 0l-.813-2.846a3.75 3.75 0 0 0-2.576-2.576l-2.846-.813a.75.75 0 0 1 0-1.442l2.846-.813A3.75 3.75 0 0 0 7.466 7.89l.813-2.846A.75.75 0 0 1 9 4.5ZM18 1.5a.75.75 0 0 1 .728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 0 1 0 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 0 1-1.456 0l-.258-1.036a2.625 2.625 0 0 0-1.91-1.91l-1.036-.258a.75.75 0 0 1 0-1.456l1.036-.258a2.625 2.625 0 0 0 1.91-1.91l.258-1.036A.75.75 0 0 1 18 1.5ZM16.5 15a.75.75 0 0 1 .712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 0 1 0 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 0 1-1.422 0l-.395-1.183a1.5 1.5 0 0 0-.948-.948l-1.183-.395a.75.75 0 0 1 0-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0 1 16.5 15Z" clip-rule="evenodd" />
                                      </svg>                                                                           
                                    <span>Offers</span> 
                                </a>
                            </li>
                            <li>
                                <a href="/profile" class="block px-4 py-3 font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('profile') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-solid fa-user  mr-1"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="/my-booking" class="block px-4 py-3 items-center md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('my-booking') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-solid fa-tags mr-1"></i>
                                    Booking-Ku
                                </a>
                            </li>
                            <li>
                                <a href="/my-deposite" class="block px-4 py-3 items-center md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('my-deposite') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-solid fa-tags mr-1"></i>
                                    Pembayaran
                                </a>
                            </li>
                            <li>
                                <a href="/ulasan/form" class="block px-4 py-3 font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('ulasan/form') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-regular fa-comment mr-1"></i>
                                    Beri Ulasan
                                </a>
                            </li>
                            <div class="horizontal-line py-4">
                                <span class="block w-full h-[2px] bg-gray-300"></span>
                            </div>
                            
                            <li x-data="{ showModal: false }">
                                <button @click="showModal = true" class="block w-full items-center text-left px-4 py-3 font-medium text-rose-700 hover:text-rose-800 hover:bg-gray-100 focus:bg-gray-200">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>
                                    Logout
                                </button>
                
                                <!-- Popup Konfirmasi -->
                                <div 
                                    x-show="showModal" 
                                    class="fixed inset-0 flex items-center justify-center font-medium bg-gray-900 bg-opacity-50 z-50 px-4"
                                >
                                    <div class="bg-white w-full md:w-1/3 p-6 rounded shadow-lg">
                                        <h2 class="text-lg font-bold mb-4">Konfirmasi Logout</h2>
                                        <p class="mb-6">Apakah Anda yakin ingin keluar?</p>
                                        <div class="flex justify-end space-x-4">
                                            <button 
                                                @click="showModal = false" 
                                                class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded focus:scale-95 duration-300"
                                            >
                                                Batal
                                            </button>
                                            <form action="{{ route('logout') }}" method="GET">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded focus:scale-95 duration-300">
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
    
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="min-h-schreen">
            <main class="container mx-auto mt-0 lg:mt-4 font-poppins">
                @yield('content')
            </main>
        </div>

        
    </div>
    
    <!-- Footer -->
    <div class="mt-16 bottom-0 w-full ">
        @include('components.footer')
    </div>

</body>
</html>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    }
</script>
