<!-- About Us Section -->
<section id="about" class="flex items-center justify-center bg-gray-50 h-[80vh] px-4">
    <div data-aos="fade-up" data-aos-duration="1500"  class="max-w-3xl mx-4 lg:mx-auto">
        <div class="text-center text-sm">
            <!-- Text -->
                <h2 class="text-5xl font-bold text-gray-800 font-playfair mb-8"><span class="text-rose-800">{{ $hotelSetting->name }} </span> Hotel</h2>

                <div class="space-y-3 md:text-xs">
                    {{ $hotelSetting->description }}                   
                </div>
        </div>
    </div>
</section>
