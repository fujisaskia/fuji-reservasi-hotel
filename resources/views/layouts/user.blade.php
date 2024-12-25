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
<body class="h-full font-poppins bg-slate-50">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-white shadow-lg p-4 text-xs font-semibold lg:relative top-0 z-10">
            <div class="max-w-6xl mx-auto flex justify-between items-center">
                <!-- Left: Logo or Title -->
                <a href="{{ url('/') }}" class="flex space-x-2 items-center text-lg tracking-wide md:text-xl font-bold text-gray-800">
                    <img src="{{ $hotelSetting->logo_path ? asset('storage/' . $hotelSetting->logo_path) : asset('assets/default-logo.png') }}" 
                    alt="{{ $hotelSetting->name ?? 'Default Logo' }}" class="w-8 h-auto">
                    <span class="font-semibold text-lg text-gray-800 font-playfair"><span class="text-rose-800">{{ $hotelSetting->name ?? 'Hotel' }}</span> Hotel</span>
                </a>
    
                <div class="relative md:inline-block text-left hidden">
                    <!-- Button -->
                    <button onclick="toggleDropdown()" class="flex space-x-2 items-center text-sm lg:text-xs text-rose-700 bg-gray-200 hover:bg-gray-300 py-2 px-3 rounded-full">
                        <i class="fa-regular fa-user"></i>
                        <p class="hidden md:flex">{{ Auth::user()->full_name }}</p>
                        <i class="flex md:hidden fa-solid fa-chevron-down"></i>
                    </button>
                
                    <!-- Dropdown menu -->
                    <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                        <ul class="py-1">
                            <li>
                                <a href="/profile" class="block px-4 py-3 text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="/my-booking" class="block px-4 py-3 text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100">
                                    Booking-Ku
                                </a>
                            </li>
                            <li x-data="{ showModal: false }" class="">
                                <button @click="showModal = true" class="block w-full text-left px-4 py-3 border-t text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100">
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
                                                class="bg-gray-300 px-4 py-2 rounded"
                                            >
                                                Batal
                                            </button>
                                            <form action="{{ route('logout') }}" method="GET">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
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
                    <button @click="showSidebar = true" class="p-2 bg-gray-200 rounded text-gray-700">
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
                        <div class="flex justify-between items-center p-4 border-b">
                            <h2 class="text-lg font-bold sr-only">Menu</h2>
                            <button @click="showSidebar = false" class="text-gray-600 hover:text-gray-800">
                                ✖
                            </button>
                        </div>
                
                        <!-- Isi Menu -->
                        <ul class="py-12 px-4 space-y-2">
                            <li>
                                <a href="/profile" class="block px-4 py-3 text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('profile') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-solid fa-user  mr-1"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="/my-booking" class="block px-4 py-3 text-sm items-center md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('my-booking') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-solid fa-tags mr-1"></i>
                                    Booking-Ku
                                </a>
                            </li>
                            <li>
                                <a href="/ulasan/form" class="block px-4 py-3 text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 {{ Request::is('ulasan/form') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">
                                    <i class="fa-regular fa-comment mr-1"></i>
                                    Beri Ulasan
                                </a>
                            </li>
                            <li x-data="{ showModal: false }">
                                <button @click="showModal = true" class="block w-full text-left px-4 py-6 border-t text-sm md:text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                
                                <!-- Popup Konfirmasi -->
                                <div 
                                    x-show="showModal" 
                                    class="fixed inset-0 flex items-center justify-center text-xs font-medium bg-gray-900 bg-opacity-50 z-50 px-4"
                                >
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
                                            <form action="{{ route('logout') }}" method="GET">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
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
