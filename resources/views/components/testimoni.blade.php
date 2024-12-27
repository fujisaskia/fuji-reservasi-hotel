<!-- Testimonial Section -->
<section id="testimonials" class="py-16 bg-gray-200 text-sm md:text-xs px-4">
    <div class="max-w-6xl mx-4 lg:mx-auto">
        <div data-aos="fade-up" data-aos-duration="1500"  class="text-center space-y-4 mb-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 font-playfair">Apa yang Orang katakan ?</h2>
            <div class="border-t-2 border-rose-500 mt-2 w-16 mx-auto"></div>
        </div>

        <!-- Wrapper for the testimonials and the buttons -->
        <div data-aos="fade-up" data-aos-duration="1500"  class="relative">
            <!-- Testimonial Scroll Container -->
            <div class="overflow-hidden flex space-x-4 py-6" id="testimonial-container">
                @foreach ($ulasans as $ulasan)           
                <!-- Testimonial Item -->
                <div class="bg-white p-4 rounded-lg shadow-xl text-center space-y-3 group hover:-translate-y-1 duration-300 min-w-[300px]">
                    <div class="flex items-start text-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-10">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-xs font-bold group-hover:text-red-900">{{ $ulasan->user->full_name }}</h3>
                            <div class="flex items-center text-yellow-500">
                                <span class="flex text-yellow-500 text-lg">
                                    {{ $ulasan->formattedRating() }}
                                </span>
                            </div>
                        </div>                              
                    </div>
                    <q class="text-left block text-[11px] text-gray-600">{{ $ulasan->comment }}</q>
                </div>
                @endforeach
            </div>

            <!-- Left Arrow Button -->
            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white p-2 rounded-full" onclick="scrollTestimonials('left')">
                ←
            </button>

            <!-- Right Arrow Button -->
            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white p-2 rounded-full" onclick="scrollTestimonials('right')">
                →
            </button>
        </div>
    </div>
</section>

<script>
    const container = document.getElementById('testimonial-container');
    const scrollAmount = 300; // Adjust how much you want to scroll per click

    // Function to scroll testimonials manually
    function scrollTestimonials(direction) {
        if (direction === 'left') {
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        } else {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    }

    // Auto-scroll function
    let autoScroll = setInterval(() => {
        container.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }, 3000); // Scroll every 3 seconds

    // Stop auto-scrolling when user interacts with the container
    container.addEventListener('mouseover', () => {
        clearInterval(autoScroll);
    });

    container.addEventListener('mouseleave', () => {
        autoScroll = setInterval(() => {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }, 3000); // Restart auto-scroll when mouse leaves
    });
</script>
