<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/app.css')
    <title>@yield('title', 'Default Title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- alpinejs --}}
    <script src="//unpkg.com/alpinejs" defer></script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Flatpickr date--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        input[disabled]:hover, 
        select[disabled]:hover {
            cursor: not-allowed;
            border-color: red; /* Warna border saat hover pada elemen disabled */
        }
    </style>

</head>
<body class="font-poppins bg-gray-200">

    @include('components.sidebar-receptionist')

    <div class="min-h-screen py-24 mx-4"> <!-- Tambahkan min-h-screen untuk membuat konten minimal setinggi layar -->
        <!-- Main Content -->
        <main class="flex-1 md:ml-52 mb-8">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="w-full bg-white shadow-md mt-10 bottom-0">
        <div class="md:ml-52 py-4 px-4 text-xs">
            <div class="text-start text-xs text-slate-400">
                <p>&copy; 2024 HOTEL RUBY. All rights reserved.</p>
                <p class="mt-2">123 Ruby St, Luxury City, 56789 | (123) 456-7890 | info@hotelruby.com</p>
            </div>
        </div>
    </footer>


</body>

</html>
