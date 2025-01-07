            {{-- Rooms Section--}}
                {{-- room card --}}
                <div id="roomsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 lg:px-0">
                    @foreach($roomTypes as $roomType)
                    <div class="room-card p-4 rounded-lg shadow-md 
                        {{ $roomType->available_rooms_count > 0 ? 'bg-white' : 'bg-gray-300' }} 
                        flex flex-col justify-between min-h-[300px] shadow-sm hover:shadow-yellow-300 duration-300" 
                        data-room-type="{{ $roomType->id }}" data-nights="1">
                        
                        {{-- Room Image --}}
                        <div class="mb-4">
                            @php
                            $fotos = json_decode($roomType->foto);
                            @endphp
                            @if ($fotos)
                                @foreach($fotos as $foto)
                                    <img src="{{ asset('storage/' . $foto) }}" 
                                         alt="Room Image" 
                                         class="rounded-xl w-full h-48 object-cover">
                                @endforeach
                            @else
                                <img src="https://via.placeholder.com/400x250" 
                                     alt="Room Image" 
                                     class="rounded-xl w-full h-48 object-cover">
                            @endif
                        </div>
                    
                        {{-- Room Info --}}
                        <div class="flex flex-col space-y-2">
                            <h4 class="text-base font-semibold uppercase font-playfair">{{ $roomType->tipe_kamar }}</h4>
                            <h3 class="text-base font-semibold">
                                IDR <span id="roomPrice" class="text-rose-800" data-base-price="{{ $roomType->harga }}">{{ number_format($roomType->harga, 0, ',', ',') }}</span>
                                <span class="text-[11px] font-medium text-gray-600">/Kamar<span id="displayNights">/</span>Malam</span>
                            </h3>                                                              
                            <div class="text-[11px]">
                                <span><i class="fas fa-user"></i> {{ $roomType->kapasitas }} Orang</span>
                            </div>
                        </div>
                        <a href="/booking/{{ $roomType->id }}" class="mt-3">
                            <button class="w-full p-2 bg-gradient-to-r from-rose-600 to-yellow-600 text-white rounded-t-lg hover:from-rose-700 hover:to-yellow-700 focus:scale-95 transition duration-300">Pilih</button>
                        </a>
                    </div>
                    @endforeach
                </div>
                
            {{-- room section end --}}      
        