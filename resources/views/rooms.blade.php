<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    {{-- Data Aos Animate --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
     <!-- Font Awesome CDN -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     {{-- ALpine.JS --}}
     <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>HOTEL</title>
</head>
<body class="h-full font-poppins">

  <div class="min-h-full text-xs">

        @include('components.navbar-lp')
        @php
            use Illuminate\Support\Str;
        @endphp
        
        <div class="relative bg-fixed bg-center h-64" style="background-image: url('{{ asset('assets/hotel.jpg') }}');">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center font-playfair">
                <h1 class="text-4xl md:text-6xl font-bold text-white">Rooms & Suites</h1>
            </div>
        </div>
        
        <div class="container mx-auto p-6 max-w-5xl my-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                @foreach ($roomTypes as $type)             
                <!-- Card 1 -->
                <div data-aos="fade-up" data-aos-duration="1500"  class="relative bg-white shadow-lg text-xs rounded-lg overflow-hidden border border-gray-200 transform hover:shadow-2xl hover:shadow-yellow-100 transition duration-300">
                    <!-- Header dengan Garis Emas -->
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-yellow-500 to-yellow-700"></div>
                    <!-- Gambar -->
                    <div class="relative">
                        @php
                            $fotos = json_decode($type->foto); // Mendekode JSON dari kolom `foto`
                        @endphp
                        <img src="{{ $fotos && count($fotos) > 0 ? asset('storage/' . $fotos[0]) : 'https://via.placeholder.com/300' }}" alt="Room Image" class="w-full h-48 object-cover rounded-t-lg">
                        <!-- Badge Kecil -->
                        {{-- <div class="absolute top-3 right-3 bg-rose-800 text-white text-xs px-3 py-1 rounded-full shadow-lg">
                            Superior
                        </div> --}}
                    </div>
                    <!-- Konten -->
                    <div class="p-6 space-y-4">
                        <h3 class="text-2xl font-extrabold text-gray-800 font-playfair border-b-2 border-yellow-500 pb-2">{{ $type->tipe_kamar }}</h3>
                        <p class="text-gray-800">
                            <span class="block text-gray-600">Tonight Rate</span>
                            <strong class="text-2xl text-rose-900">IDR {{ number_format($type->harga, '0', ',', ',') }} <span class="text-xs">/ Malam</span></strong>
                        </p>
                        <p class="text-gray-600 leading-relaxed">{{ Str::limit($type->deskripsi_kamar, 150) }}</p>
                    </div>
                    <!-- Footer dengan Button Mewah -->
                    <div class="flex justify-end text-center p-4 bg-gray-100 text-sm md:text-xs">
                        <a href="/login" class="bg-gradient-to-r w-1/3 from-rose-800 to-yellow-500 text-white font-semibold px-5 py-2 rounded-lg shadow-lg transform focus:scale-95 hover:-translate-x-1 transition duration-300">
                            Book Now
                        </a>
                    </div>
                </div>
                
                @endforeach
            </div>
        </div>
          

        <x-footer></x-footer>
  </div>

      {{-- Data Aos Animate --}}
      <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
      <script>
          AOS.init();
      </script>
    
  
</body>
</html>