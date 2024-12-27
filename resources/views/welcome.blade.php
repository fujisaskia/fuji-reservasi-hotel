<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 scroll-smooth">
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

    <title>{{ $hotelSetting->name }}</title>
</head>
<body class="h-full font-poppins">

  <div class="min-h-full">

        @include('components.navbar-lp')
        @include('components.hero')
        
        @include('components.about')

        <div class="max-w-6xl mx-auto py-10 px-6">
          <div data-aos="fade-up" data-aos-duration="1500"  class="text-left mb-10">
              <p class="text-sm uppercase tracking-wide text-gray-500">{{ $hotelSetting->name }}</p>
              <h1 class="font-playfair text-4xl font-bold text-gray-800">Rooms & Suites</h1>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
              @foreach($roomTypes as $room)
                  <!-- Card -->
                  <div  data-aos="fade-up" data-aos-duration="2000"  class="relative group">
                      @php
                      $fotos = json_decode($room->foto); // Mendekode JSON dari kolom `foto`
                      @endphp
                    <img src="{{ $fotos && count($fotos) > 0 ? asset('storage/' . $fotos[0]) : 'https://via.placeholder.com/300' }}" 
                     alt="{{ $room->tipe_kamar }}" 
                     class="w-full h-72 object-cover rounded-b-xl">
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-50 transition duration-300 rounded-lg"></div>
                      <div class="absolute inset-0 flex flex-col justify-between p-4">
                          <div class="text-right">
                              <button class="py-1 px-4 bg-white hover:bg-rose-800 hover:text-white text-xs font-medium text-gray-800 uppercase tracking-wide rounded-l-lg hover:-translate-x-1 focus:scale-95 shadow duration-300">Book</button>
                          </div>
                          <div class="group-hover:-translate-y-6 duration-500">
                              <h2 class="text-white text-lg font-semibold font-playfair">{{ $room->tipe_kamar }}</h2>
                              <a href="/rooms" class="text-xs text-white underline">Details</a>
                          </div>
                      </div>
                  </div>
              @endforeach
          </div>
        </div>
      
        @include('components.testimoni')
        @include('components.contact')
        @include('components.footer')

  </div>

    {{-- Data Aos Animate --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>
  
</body>
</html>