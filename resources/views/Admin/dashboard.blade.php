<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'dashboard | Admin')

@section('content')

    <h4 class="bg-white py-3 px-4 text-sm md:text-lg rounded-lg shadow-md md:w-1/2 mb-8 font-semibold">Haloo... Selamat Datang, <span class="font-semibold text-rose-600">{{ Auth::user()->full_name }}!</span></h4>
    
    <div class="container mx-auto bg-white py-8 px-4 md:px-6 rounded-lg items-center justify-center md:justify-start text-xs">

        <!-- Menampilkan status kamar -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2 lg:gap-6 pb-12 border-b">
            <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-rose-800 to-rose-500 text-white hover:shadow-xl rounded-md duration-300">
                <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
                    <i class="fa-solid fa-tags fa-4x"></i>
                    <div class="lg:text-start">
                        <h4 class="text-2xl font-semibold">{{ $totalPaymentSuccess }}</h4>
                        <p>total reservasi bulan ini</p>                        
                    </div>
                </div>  
            </div>


            <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-green-600 to-green-400 text-white hover:shadow-xl rounded-md duration-300">
                <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
                    <i class="fa-solid fa-tags fa-4x"></i>
                    <div class="lg:text-start">
                        <h4 class="text-2xl font-semibold">{{ $totalConfirmed }}</h4>
                        <p>reservasi dikonfirmasi</p>                        
                    </div>
                </div>  
            </div>

            <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-yellow-800 to-yellow-500 text-white hover:shadow-xl rounded-md duration-300">
                <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
                    <i class="fa-solid fa-tags fa-4x"></i>
                    <div class="lg:text-start">
                        <h4 class="text-2xl font-semibold">{{ $totalPending }}</h4>
                        <p>reservasi pending</p>                        
                    </div>
                </div>  
            </div>
            <div class="text-start justify-start items-start p-8 bg-gradient-to-l from-gray-800 to-gray-500 text-white hover:shadow-xl rounded-md duration-300">
                <div class="flex space-x-2 lg:space-x-6 items-center mx-auto justify-center">
                    <i class="fa-solid fa-tags fa-4x"></i>
                    <div class="lg:text-start">
                        <h4 class="text-2xl font-semibold">{{ $totalCancelled }}</h4>
                        <p>reservasi dibatalkan</p>                        
                    </div>
                </div>  
            </div>
        </div>

        <div class="max-w-4xl mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
            <h2 class="text-lg md:text-2xl font-bold text-center mb-4">Grafik Pendapatan Hotel Bulanan</h2>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>

        {{-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- Tabel Customer Check In -->
            <div class=" bg-white p-4 shadow-md rounded-md">
                <h3 class="text-lg font-semibold mb-4">Customer Check In</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead>
                            <tr class="border-b font-semibold">
                                <th class="p-3 text-left text-gray-600">Customer Name</th>
                                <th class="p-3 text-left text-gray-600"># Rooms</th>
                                <th class="p-3 text-left text-gray-600">Date / Time Check-In</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b font-medium hover:bg-slate-100">
                                <td class="p-3 text-left text-gray-600">John Doe</td>
                                <td class="p-3 text-left text-gray-600">15</td>
                                <td class="p-3 text-left text-gray-600">30-10-2024</td>
                            </tr>
                            <tr class="border-b">
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                    <div class="flex justify-between mt-4">
                        <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                            <i class="fa-solid fa-angles-left group-hover:-translate-x-1 duration-500"></i>
                            <span>Previous</span>
                        </button>
                        <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                            <span>Next</span>
                            <i class="fa-solid fa-angles-right group-hover:translate-x-1 duration-500"></i>
                        </button>
                    </div>
            </div>
        
            <!-- Tabel Customer Check Out -->
            <div class=" bg-white p-4 shadow-md rounded-md">
                <h3 class="text-lg font-semibold mb-4">Customer Check Out Today</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead>
                            <tr class="border-b font-semibold">
                                <th class="p-3 text-left text-gray-600">Customer Name</th>
                                <th class="p-3 text-left text-gray-600"># Rooms</th>
                                <th class="p-3 text-left text-gray-600">Date / Time Check-Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b font-medium hover:bg-slate-100">
                                <td class="p-3 text-left text-gray-600">John Doe</td>
                                <td class="p-3 text-left text-gray-600">15</td>
                                <td class="p-3 text-left text-gray-600">31-10-2024</td>
                            </tr>
                            <tr class="border-b">
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">No Data Customer Checkout Found</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-between mt-4">
                        <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                            <i class="fa-solid fa-angles-left group-hover:-translate-x-1 duration-500"></i>
                            <span>Previous</span>
                        </button>
                        <button class="flex items-center space-x-1 p-3 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded duration-300 group">
                            <span>Next</span>
                            <i class="fa-solid fa-angles-right group-hover:translate-x-1 duration-500"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
        

    </div>

    <script>
// Ambil data dari API Laravel
fetch('/monthly-revenue')
  .then(response => response.json())
  .then(data => {
    const ctx2 = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx2, {
      type: 'bar', // Jenis grafik
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Bulan-bulan
        datasets: [
          {
            label: 'Pendapatan dari Kamar',
            data: data.roomRevenue, // Data pendapatan kamar dari API
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 1,
          },
          {
            label: 'Pendapatan dari Layanan Kamar',
            data: data.serviceRevenue, // Data pendapatan layanan dari API
            backgroundColor: 'rgba(255, 159, 64, 0.5)',
            borderColor: 'rgb(255, 159, 64)',
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Pendapatan (Rp)',
            },
          },
          x: {
            title: {
              display: true,
              text: 'Bulan',
            },
          },
        },
      },
    });
  })
  .catch(error => console.error('Error fetching revenue data:', error));

    </script>
    



@endsection
    
