<!-- Filter berdasarkan tipe kamar -->
<div class="flex space-x-4 items-center mb-5">
 <p>Pilih Tipe Kamar</p>
 <select id="roomTypeFilter" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:border-yellow-300 block w-2/3 lg:w-1/4 p-2">
     <option value="">All Types</option>
     @foreach ($roomTypes as $type)
         <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->tipe_kamar }}</option>
     @endforeach
 </select>
</div>

<!-- Menampilkan status kamar -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2 lg:gap-4 pb-12 border-b">
    <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-rose-800 to-rose-500 text-white hover:shadow-xl rounded-md duration-300">
        <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
            <i class="fa-solid fa-bed fa-4x"></i>
            <div class="lg:text-start">
                <h4 class="text-2xl font-semibold">{{ $countOccupied }}</h4>
                <p class="">kamar terisi</p>                        
            </div>
        </div>  
    </div>


    <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-green-600 to-green-400 text-white hover:shadow-xl rounded-md duration-300">
        <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
            <i class="fa-solid fa-bed fa-4x"></i>
            <div class="lg:text-start">
                <h4 class="text-2xl font-semibold">{{ $countAvailable }}</h4>
                <p class="">kamar tersedia</p>                        
            </div>
        </div>  
    </div>

    <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-yellow-800 to-yellow-500 text-white hover:shadow-xl rounded-md duration-300">
        <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
            <i class="fa-solid fa-bed fa-4x"></i>
            <div class="lg:text-start">
                <h4 class="text-2xl font-semibold">{{ $countCleaning }}</h4>
                <p class="">kamar perawatan</p>                        
            </div>
        </div>  
    </div>
    <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-blue-800 to-blue-500 text-white hover:shadow-xl rounded-md duration-300">
        <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
            <i class="fa-solid fa-bed fa-4x"></i>
            <div class="lg:text-start">
                <h4 class="text-2xl font-semibold">{{ $countOccupied }}</h4>
                <p class="">Tamu Menginap</p>                        
            </div>
        </div>  
    </div>
</div>

<!-- Javascript untuk update data berdasarkan tipe kamar -->
<script>
    document.getElementById('roomTypeFilter').addEventListener('change', function() {
        window.location.href = "?room_type_id=" + this.value;
    });
</script>
