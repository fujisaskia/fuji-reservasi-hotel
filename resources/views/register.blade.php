<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
     <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     {{-- alpinejs --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    <title>Create Account Member</title>

</head>
<body class="font-poppins text-sm lg:text-xs">
    <div class="w-screen h-screen bg-yellow-100 flex items-center justify-center p-6">
        <div class="bg-white w-full lg:w-2/3 rounded-lg shadow-lg p-6 md:p-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="order-2 lg:order-1">
              {{-- title --}}
              <h2 class="text-center text-xl font-bold mb-4 font-playfair pb-2 border-b border-gray-300">
                  Register Member
              </h2>
              {{-- form input --}}
              <form  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-4">
                  <label for="full_name" class="block  font-medium text-gray-700">Nama</label>
                  <div class="flex gap-2">
                      <select name="title" class="mt-1 mb-2 block w-1/4 p-2 border focus:border-rose-600 rounded-lg shadow-sm">
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Ms.">Ms.</option>
                      </select>                  
                      <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 mb-2 block w-3/4 p-2 border focus:border-rose-600 rounded-lg shadow-sm" placeholder="Masukkan Nama Anda">
                    </div>                
                    @error('full_name')
                      <span class="text-red-700 text-[11px] p-1 bg-red-100 rounded w-full">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                  <label for="email" class="block  font-medium text-gray-700">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}" class="mt-1 mb-2 block w-full p-2 border focus:border-rose-600 rounded-lg shadow-sm" placeholder="email-anda@gmail.com">
                  @error('email')
                    <span class="text-red-700 text-[11px] p-1 bg-red-100 rounded w-full">{{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="password" class="block  font-medium text-gray-700">Password</label>
                  <input type="password" name="password" class="mt-1 mb-2 block w-full p-2 border rounded-lg shadow-sm" placeholder="Masukkan Password">
                  @error('password')
                    <span class="text-red-700 text-[11px] p-1 bg-red-100 rounded w-full">{{ $message }}</span>
                  @enderror
                </div>

                <div class="flex flex-col md:flex-row md:space-x-3 mb-4">
                  <div class="flex-1">
                    <label for="phone" class="block font-medium text-gray-700">Nomor Telepon</label>
                    <input type="number" name="phone_number" value="{{ old('phone_number') }}" class="mt-1 mb-2 block w-full p-2 border rounded-lg shadow-sm" placeholder="Nomor Telepon Anda">
                    @error('phone_number')
                      <span class="text-red-700 text-[11px] p-1 bg-red-100 rounded w-full">{{ $message }}</span>
                    @enderror
                  </div>
  
                  <div class="">
                      <label for="nationality" class="block  font-medium text-gray-700">nationality</label>
                      <input type="text" name="nationality" value="{{ old('nationality') }}" class="mt-1 mb-2 block w-full p-2 border rounded-lg shadow-sm" placeholder="Asal Negara Anda">
                      @error('nationality')
                        <span class="text-red-700 text-[11px] p-1 bg-red-100 rounded w-full">{{ $message }}</span>
                      @enderror
                  </div>
                </div>

                <div class=" mb-4">
                  <div class="flex space-x-3">
                    <div class="">
                        <label for="identification_type" class="block font-medium text-gray-700">Tipe Identitas</label>
                        <select id="identification_type" name="identification_type" class="mt-1 mb-2 block w-full p-2 border rounded-lg shadow-sm">
                            <option value="KTP">KTP</option>
                            <option value="PASSPORT">PASSPORT</option>
                        </select>
                    </div>
                    
                    <div class="flex-1">
                        <label for="identification_number" class="block font-medium text-gray-700">Nomor Identitas</label>
                        <input type="text" id="identification_number" name="identification_number" value="{{ old('identification_number') }}" class="mt-1 mb-2 block w-full p-2 border rounded-lg shadow-sm" placeholder="Masukkan Nomor Identitas">
                    </div>
                  </div>
                  @error('identification_number')
                    <span class="text-red-700 text-[11px] p-1 bg-red-100 rounded w-full">{{ $message }}</span>
                  @enderror
                </div>
            
                <div class="mt-6 mx-8">
                    <button type="submit" class="font-bold w-full bg-rose-700 text-white p-2 rounded-lg hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-600 focus:ring-offset-2 transition-colors duration-200">
                      Register
                    </button>
                </div>
                
  
              </form>
              {{-- anchor  register--}}
              <div class="mt-6 text-center">
                <p class=" text-gray-600">Sudah punya akun? <a href="/login" class="text-blue-700 hover:text-blue-500 font-semibold">Login</a></p>
              </div>

            </div>

            <div class="flex justify-center items-center order-1 lg:order-2">
              <img src="{{ asset('assets/ruby-hotel.png') }}" alt="" class="w-20 md:w-40 h-auto">
            </div>
          
          </div>
        </div>
    </div>
      
</body>
</html>