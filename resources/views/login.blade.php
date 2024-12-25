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

    <title>Login</title>

</head>
<body class="font-poppins text-sm lg:text-xs">
    <div class="w-screen h-screen bg-rose-50 flex items-center justify-center p-6">
        <div class="bg-white w-full lg:w-2/3 rounded-lg shadow-lg p-6 md:p-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="flex justify-center items-center">
              <img src="{{ asset('assets/ruby-hotel.png') }}" alt="" class="w-20 md:w-40 h-auto">
            </div>
          
            <div class="">
              {{-- title --}}
              <h2 class="text-center text-2xl font-bold mb-4 font-playfair pb-2 border-b border-gray-300">
                  Login Member
              </h2>
              {{-- form input --}}
              <form  action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                  <label for="email" class="block  font-medium text-gray-700">Email</label>
                  <input type="email" name="email" class="mt-1 block w-full p-2 border focus:border-rose-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="your@email.com">
                  @error('email')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="password" class="block  font-medium text-gray-700">Password</label>
                  <input type="password" name="password" class="mt-1 block w-full p-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="masukkan password">
                  @error('password')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                  @enderror
                </div>
            
                <div class="mt-6 mx-8">
                    <button type="submit" class="font-bold w-full bg-rose-500 text-white p-2 rounded-lg hover:bg-rose-700 focus:outline-none focus:scale-95 duration-200">
                      Masuk!
                    </button>
                </div>
                
  
              </form>
              {{-- anchor  register--}}
              <div class="mt-6 text-center">
                <p class=" text-gray-600">Belum punya akun? <a href="/register" class="text-blue-700 hover:text-blue-500 font-semibold">Daftar</a></p>
              </div>

            </div>
          </div>
        </div>
    </div>
    
      
</body>
</html>