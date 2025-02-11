<!-- resources/views/hotel.blade.php -->
@extends('layouts.user')

@section('content')

<div class="max-w-6xl mx-auto text-sm lg:text-xs p-6 md:p-12 lg:p-0 h-screen">
    
    @if(session('success'))
    <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
            });
        </script>
    @endif


    <div class="flex gap-4">
        
        <x-menu-profile></x-menu-profile>
        <div class="w-full flex flex-col justify-end">
            <!-- Right Section -->
            <div class="flex-1 space-y-4">
                <!-- Edit Information Section -->
                <div class="space-y-6 bg-white shadow-md px-6 py-8 rounded-lg border border-gray-200">
                    <h2 class="text-lg font-semibold mb-4 pb-4 border-b border-gray-300">Edit Your Information</h2>
        
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-4">
                                <div class="flex space-x-4">
                                    <!-- Title and Full Name -->
                                    <div class="space-y-2 pb-1 border-b border-gray-300">
                                        <label for="title" class="text-gray-700 text-[11px] font-medium">Title</label>
                                        <select name="title" id="title" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                            <option value="Mr" {{ old('title', $user->title) == 'Mr' ? 'selected' : '' }}>Mr</option>
                                            <option value="Mrs" {{ old('title', $user->title) == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                            <option value="Ms" {{ old('title', $user->title) == 'Ms' ? 'selected' : '' }}>Ms</option>
                                        </select>
                                                                            </div>
                                    <div class="flex-1 space-y-2 pb-1 border-b border-gray-300">
                                        <label for="full_name" class="text-gray-700 text-[11px] font-medium">Full Name</label>
                                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                    </div>
                                </div>
                                <div class="space-y-2 pb-1 border-b border-gray-300">
                                    <label for="email" class="text-gray-700 text-[11px] font-medium">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                </div>
                            </div>
        
                            <div class="space-y-4">
                                <div class="flex space-x-4 justify-between">
                                    <div class="w-full space-y-2 pb-1 border-b border-gray-300">
                                        <label for="nationality" class="text-gray-700 text-[11px] font-medium">Nationality</label>
                                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $user->nationality) }}" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                    </div>
                                    <div class="w-full space-y-2 pb-1 border-b border-gray-300">
                                        <label for="phone_number" class="text-gray-700 text-[11px] font-medium">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                    </div>
                                </div>
                                <div class="flex space-x-6">
                                    <div class="space-y-2 pb-1 border-b border-gray-300">
                                        <label for="identification_type" class="text-gray-700 text-[11px] font-medium">Identify Type</label>
                                        <select name="identification_type" id="identification_type" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                            <option value="KTP" {{ old('identification_type', $user->identification_type) == 'KTP' ? 'selected' : '' }}>KTP</option>
                                            <option value="Passport" {{ old('identification_type', $user->identification_type) == 'KTP' ? '' : 'selected' }}>Passport</option>
                                        </select>                                        
                                    </div>
                                    <div class="space-y-2 pb-1 border-b border-gray-300">
                                        <label for="identification_number" class="text-gray-700 text-[11px] font-medium">Identify Number</label>
                                        <input type="text" name="identification_number" id="identification_number" value="{{ old('identification_number', $user->identification_number) }}" class="w-full px-2 py-1 focus:outline-none rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="ml-auto justify-end px-4 py-2 bg-yellow-500 hover:bg-yellow-600 mb-2 rounded-md">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        

    </div>
</div>

@endsection


