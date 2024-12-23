        <!-- left Section -->
        <div class="hidden lg:block w-full lg:w-1/4 bg-white p-4 rounded-lg shadow-md sticky top-16 self-start">
            <!-- Tabs -->
            <div class="">
                <a href="/my-booking">
                    <button class="w-full text-left p-3 hover:bg-gray-100 {{ Request::is('my-booking') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">Booking-Ku</button>
                </a>
                <a href="/profile">
                    <button class="w-full text-left p-3 hover:bg-gray-100 mt-1 {{ Request::is('profile') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">Ubah Profile</button>
                </a>
                <a href="/ulasan/form" class="">
                    <button class="w-full text-left p-3 hover:bg-gray-100 mt-1 {{ Request::is('ulasan/form') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">Beri Ulasan</button>
                </a>
                <a href="/profile" class="pt-4 border-t mt-5">
                    <button class="w-full text-left p-3 text-rose-800 hover:bg-gray-100 mt-1 {{ Request::is('') ? 'bg-gray-100 border-l-4 border-rose-600' : 'hover:bg-gray-100' }}">Logout</button>
                </a>
            </div>
        </div>