<section id="contact" class="max-w-5xl mx-auto h-[500px] grid grid-cols-1 lg:grid-cols-2 gap-4 items-center justify-center p-6">
    <h1 data-aos="fade-up" data-aos-duration="1500"  class="text-4xl lg:text-5xl font-bold text-gray-800 text-left font-playfair">Contact Us</h1>

    <div data-aos="fade-right" data-aos-duration="1500"  class="lg:border-l-2 w-full max-w-md p-2">
        <h2 class="text-sm font-semibold text-gray-700 mb-2">ALamat</h2>
        <p class="text-gray-600 leading-relaxed">
            {{ $hotelSetting->address }}
        </p>

        <h2 class="text-sm font-semibold text-gray-700 mt-6 mb-2">Kontak</h2>
        <p class="text-gray-600 text-base font-medium">{{ $hotelSetting->phone }}</p>
        <p class="text-gray-600">{{ $hotelSetting->email }}</p>

        <h2 class="text-sm font-semibold text-gray-700 mt-6 mb-2">Follow Us</h2>
        <div class="flex space-x-6 text-gray-600">
            <a href="{{ $hotelSetting->instagram }}" class="hover:text-rose-900 text-lg hover:scale-105 duration-300"><i class="fab fa-instagram"></i></a>
            <a href="{{ $hotelSetting->facebook }}" class="hover:text-rose-900 text-lg hover:scale-105 duration-300"><i class="fab fa-facebook"></i></a>
            <a href="{{ $hotelSetting->whatsapp }}" class="hover:text-rose-900 text-lg hover:scale-105 duration-300"><i class="fab fa-youtube"></i></a>
            <a href="{{ $hotelSetting->tiktok }}" class="hover:text-rose-900 text-lg hover:scale-105 duration-300"><i class="fab fa-tiktok"></i></a>
        </div>
</section>