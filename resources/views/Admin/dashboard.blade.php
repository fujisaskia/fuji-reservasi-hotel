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

        @php
            $currentYear = date('Y');
            $years = range($currentYear, $currentYear - 4); // Tahun saat ini hingga 5 tahun terakhir
        @endphp
        
        <div class="max-w-4xl mx-auto py-12 px-6 text-xs font-poppins bg-white rounded-lg shadow-md">
            <div class="flex justify-center items-center space-x-4 mb-2">
                <h2 class="text-lg md:text-2xl font-bold text-center">Grafik Pendapatan Hotel Bulanan</h2>
                <select id="yearFilter" class="p-1.5 border rounded-r-md focus:outline-none focus:ring focus:ring-yellow-200" onchange="updateChart()">
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>            
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
        

    </div>

<script>
    let revenueChart; // Global variable for the chart instance

    function updateChart() {
        const selectedYear = document.getElementById('yearFilter').value;

        // Fetch data berdasarkan tahun yang dipilih
        fetch(`/monthly-revenue?year=${selectedYear}`)
            .then(response => response.json())
            .then(data => {
                // Update chart data
                revenueChart.data.datasets[0].data = data.roomRevenue;
                revenueChart.data.datasets[1].data = data.serviceRevenue;
                revenueChart.update(); // Refresh the chart
            })
            .catch(error => console.error('Error fetching revenue data:', error));
    }

    // Initialize the chart
    function initChart() {
        const ctx2 = document.getElementById('revenueChart').getContext('2d');
        revenueChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Pendapatan dari Kamar',
                        data: [], // Awalnya kosong
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Pendapatan dari Layanan Kamar',
                        data: [], // Awalnya kosong
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

        // Fetch initial data for the current year
        updateChart();
    }

    // Call initChart on page load
    document.addEventListener('DOMContentLoaded', initChart);


</script>
    



@endsection
    
