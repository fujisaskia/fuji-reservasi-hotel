<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'check-in | receptionist')

@section('content')
<div class="container mx-auto py-8 px-6">
    <h4 class="bg-white sticky top-24 py-3 px-4 rounded-lg shadow-md md:w-1/2 mb-8 lg:text-sm z-10">
        Pilih kamar untuk check-in
    </h4>
             
    <!-- resources/views/checkin.blade.php -->
    <div class="flex items-center justify-center md:justify-start text-xs">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($availableRooms as $room)
            <a href="{{ route('checkin.form', $room->id) }}" class="block hover:-translate-y-1 duration-300">
                <div class="text-center justify-center items-center py-6 px-12 bg-gradient-to-l from-green-700 to-green-500 text-white hover:shadow-xl rounded-md duration-300">
                        <div class="flex space-x-3 items-center mx-auto justify-center">
                            <i class="fa-solid fa-bed fa-3x"></i>
                            <div class="lg:text-start uppercase">
                                <h4 class="text-3xl font-semibold">{{ $room->room_number }}</h4>
                                <p class="uppercase">{{ optional($room->roomType)->tipe_kamar }}</p>
                            </div>
                        </div>  
                    </div>                
                </a>
            @endforeach
        </div>
    </div>
</div>




@endsection
    
