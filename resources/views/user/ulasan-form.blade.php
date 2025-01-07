@extends('layouts.user')

@section('content')
<div class="max-w-6xl mx-auto text-sm md:text-xs p-6 md:p-12 lg:p-0">
    
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
        <div class="w-full">
            
            <!-- Form Ulasan -->
            <form action="{{ route('ulasan.store') }}" method="POST" class="max-w-2xl mx-auto bg-white p-6 shadow-md rounded-md">
                @csrf
                <h1 class="text-2xl font-bold mb-6">Beri Ulasan</h1>
        
                <div class="mb-4">
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <div class="grid grid-cols-3 sm:grid-cols-2 md:grid-cols-5 gap-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}" 
                                class="rating-button flex items-center justify-center p-2 rounded border border-gray-300 bg-white hover:bg-yellow-50 focus:ring focus:ring-yellow-100">
                                @for ($j = 1; $j <= $i; $j++)
                                    <i class="fa-solid fa-star text-yellow-500"></i>
                                @endfor
                                <span class="ml-2 text-sm text-gray-700">({{ $i }})</span>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating" required />
                    @error('rating')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <script>
                    document.querySelectorAll('.rating-button').forEach(button => {
                        button.addEventListener('click', function () {
                            const value = this.getAttribute('data-value');
                            document.getElementById('rating').value = value;
                            document.querySelectorAll('.rating-button').forEach(btn => btn.classList.remove('bg-yellow-100'));
                            this.classList.add('bg-yellow-100');
                        });
                    });
                </script>
                
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Komentar</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full mt-1 p-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-yellow-100"></textarea>
                    @error('comment')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 w-1/3 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">Kirim Ulasan</button>
                </div>
            </form>
        </div>
</div>
@endsection
