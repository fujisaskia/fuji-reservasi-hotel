<!-- resources/views/home.blade.php -->
@extends('layouts/admin')

@section('title', 'detail layanan tamu')

@section('content')


<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md w-full text-sm md:text-xs">
    <!-- Header -->
    <h1 class="text-lg text-center font-semibold text-gray-700 mb-8">Daftar Layanan Kamar</h1>
    
    <!-- Informasi Tamu dan Kamar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pb-3 border-b">
      <div class="">
        <label class="text-gray-900 font-medium">Invoice:</label>
        <p class="text-sm font-semibold text-gray-800">{{ $invoice->invoice_number }}</p>
      </div>
      <div class="">
        <label class="block text-gray-600 font-medium">Nomor Kamar:</label>
        <p class="text-sm font-semibold text-gray-800">{{ $reservation->room->first()->room_number ?? 'tidak diketahui' }}</p>
      </div>
      <div class="">
        <label class="block text-gray-600 font-medium">Nama Tamu:</label>
        <p class="text-sm font-semibold text-gray-800">{{ $reservation->user->full_name ?? 'N/A' }}</p>
      </div>
    </div>
  
    <div  class="">
      <div>
        <div class="my-6">
          <div class="flex justify-start mb-5">
            <h2 class="text-sm font-semibold text-gray-700">Daftar Layanan</h2>
          </div>
          <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2 text-center">No</th>
                <th class="border border-gray-300 p-2 text-left">Nama Item</th>
                <th class="border border-gray-300 p-2 text-center">Harga</th>
                <th class="border border-gray-300 p-2 text-center">Qty</th>
                <th class="border border-gray-300 p-2 text-center">Subtotal</th>
                <th class="border border-gray-300 p-2 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($serviceOrder as $index => $order)
                <tr class="hover:bg-gray-50">
                  <td class="border text-center border-gray-300 p-2">{{ $index + 1 }}</td>
                  <td class="border border-gray-300 p-2">{{ $order->service->name ?? 'N/A' }}</td>
                  <td class="border border-gray-300 p-2 text-center">IDR {{ number_format($order->price, 0, ',', ',') }}</td>
                  <td class="border border-gray-300 p-2 text-center">{{ $order->quantity }}</td>
                  <td class="border border-gray-300 p-2 text-center">IDR {{ number_format($order->total_price, 0, ',', ',') }}</td>
                  <td class="flex justify-around items-center space-x-1 border border-gray-300 p-2 text-center">
                    <button onclick="deleteService({{ $order->id }})" class="text-rose-700 hover:text-rose-800 hover:scale-105 focus:scale-95 duration-300">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                    <input type="checkbox" name="service_ids[]" value="{{ $order->id }}" class="">
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-gray-500 p-6">Tidak ada layanan yang dipesan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <div class="flex space-x-1 justify-end p-2">
            <p>Pilih semua</p>
            <input type="checkbox" id="selectAll" onclick="selectAllCheckboxes()">
          </div>
        </div>
      
        <!-- Total Harga -->
        <div class="flex justify-end mt-4">
          <div class="border-b-2 border-gray-200 p-4 w-full max-w-md">
            <div class="flex justify-between items-center">
              <span class="text-gray-700 font-medium">Total Harga:</span>
              <span class="text-lg text-rose-900 font-semibold">IDR {{ number_format($totalHarga, 0, ',', ',') }}</span>
            </div>
          </div>
        </div>

        <div class="flex justify-end mt-4 space-x-4">
            <!-- Tombol untuk Cetak Invoice -->
            <form id="printForm" method="POST" action="{{ route('print.services') }}">
              @csrf
                <!-- Tambahkan checkbox layanan di sini -->
                @foreach($serviceOrder as $service)
                  <label class="hidden">
                    <input type="checkbox" name="service_ids[]" value="{{ $service->id }}">
                    {{ $service->name }}
                  </label>
                @endforeach
                <div id="selectedServicesContainer"></div>
                <div class="flex">
                  <button type="button" onclick="collectSelectedServices()" class="flex items-center space-x-1 bg-blue-600 text-white px-6 py-2 rounded-l-xl hover:bg-blue-700">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Layanan</span>
                  </button>
                </div>
            </form>
          
            </div>
        </div>

      
        
      </div>
      
    </div>
    
</div>



<script>

  // Script untuk memilih semua checkbox
  function selectAllCheckboxes() {
    const checkboxes = document.querySelectorAll('input[name="service_ids[]"]');
    const selectAllCheckbox = document.getElementById('selectAll');
    checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
  }

  // Function to collect selected service IDs and append them to the form as hidden inputs
  function collectSelectedServices() {
    const form = document.getElementById('printForm');
    const selectedServicesContainer = document.getElementById('selectedServicesContainer');
    selectedServicesContainer.innerHTML = ''; // Clear previous inputs

    const checkboxes = document.querySelectorAll('input[name="service_ids[]"]:checked');
    checkboxes.forEach(checkbox => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'service_ids[]'; // Pastikan nama input sesuai dengan di controller
      input.value = checkbox.value;
      selectedServicesContainer.appendChild(input);
    });

    if (checkboxes.length > 0) {
      form.submit();
    } else {
      alert('Silakan pilih layanan yang ingin dicetak.');
    }
  }


  //menghapus order service
    function deleteService(orderId) {
        if (confirm("Apakah Anda yakin ingin menghapus layanan ini?")) {
            fetch(`/delete-service`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); // Refresh halaman setelah penghapusan
            });
        }
    }

</script>
  
@endsection