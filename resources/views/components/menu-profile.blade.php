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
                <div x-data="{ showModal: false }">
                    <!-- Tombol Logout -->
                    <button 
                        @click="showModal = true" 
                        class="w-full text-left p-3 border-t text-rose-800 hover:bg-gray-100 mt-2"
                    >
                        Logout
                    </button>
                
                    <!-- Popup Konfirmasi -->
                    <div 
                        x-show="showModal" 
                        class="fixed inset-0 flex items-center justify-center text-xs bg-gray-900 bg-opacity-50 z-50 px-4"
                    >
                        <div class="bg-white w-full md:w-1/3 p-6 rounded shadow-lg">
                            <h2 class="text-lg font-bold mb-4">Konfirmasi Logout</h2>
                            <p class="mb-6">Apakah Anda yakin ingin keluar?</p>
                            <div class="flex justify-end space-x-4">
                                <!-- Tombol Batal -->
                                <button 
                                    @click="showModal = false" 
                                    class="bg-gray-300 px-4 py-2 rounded"
                                >
                                    Batal
                                </button>
                                <!-- Tombol Logout -->
                                <form action="{{ route('logout') }}" method="GET">
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