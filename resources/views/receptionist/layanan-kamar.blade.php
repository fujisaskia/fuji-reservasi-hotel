<!-- resources/views/home.blade.php -->
@extends('layouts/receptionist')

@section('title', 'Layanan Kamar')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md w-full text-xs">
  <!-- Header -->
      <h1 class="text-lg text-center  font-semibold text-gray-700 mb-8">Input Layanan Kamar</h1>
      
      <!-- Informasi Tamu dan Kamar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pb-3 border-b">
            <div class="">
                <label class="text-gray-900 font-medium">Invoice : </label>
                <p class="text-sm font-semibold text-gray-800">{{ $invoice->invoice_number }}</p>
            </div>
            <div class="">
                <label class="block text-gray-600 font-medium">Nomor Kamar :</label>
                <p class="text-sm font-semibold text-gray-800">{{ $reservation->room->first()->room_number ?? 'tidak diketahui' }}</p>
            </div>
            <div class="">
                <label class="block text-gray-600 font-medium">Nama Tamu :</label>
                <p class="text-sm font-semibold text-gray-800">{{ $reservation->user->full_name }}</p>
            </div>
        </div>

    <!-- Pilih Kategori Layanan -->
    <div class="flex space-x-4 mb-4 items-center mt-5">
        <label for="kategori" class="block text-gray-600 font-medium mb-1">Pilih Kategori Layanan:</label>
        <select id="kategori" class="flex-1 p-2 border-b-2 border-gray-100 focus:outline-none">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>


    <!-- Daftar Item Layanan -->
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-2">Daftar Item</h2>
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2 text-left">Nama Item</th>
                    <th class="border border-gray-300 p-2 text-right">Harga</th>
                    <th class="border border-gray-300 p-2 text-center">Jumlah</th>
                    <th class="border border-gray-300 p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $service->name }}</td>
                        <td class="border border-gray-300 p-2 text-right">IDR {{ number_format($service->price, '0', ',', ',') }}</td>
                        <td class="border border-gray-300 p-2 text-center">
                            <input type="number" min="1" value="1" class="w-20 border p-1.5 border-gray-300 rounded-full text-center focus:outline-none focus:ring focus:ring-yellow-100">
                        </td>
                        <td class="border border-gray-300 p-2 text-center mt-6">
                            <button class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600" data-service-id="{{ $service->id }}">Tambah</button>
                        </td>
                    </tr>
                @endforeach
                <!-- Tambahkan baris item lainnya sesuai kebutuhan -->
            </tbody>
        </table>
    </div>

  <!-- Ringkasan Pesanan -->
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-2">Ringkasan Pesanan</h2>
        <ul class="border border-gray-300 rounded-md p-4 bg-gray-50 ringkasan-pesanan">
            <!-- Item akan ditambahkan secara dinamis -->
            <li class="total-row flex justify-between items-center font-semibold text-lg text-rose-900">
                <span>Total</span>
                <span class="total-harga">IDR 0</span>
            </li>
        </ul>
    </div>


    <!-- Tombol Aksi -->
    <div class="flex justify-end">
        <button id="simpanPesanan" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600">Simpan Pesanan</button>
    </div>
</div>


<script>
    // filter kategori layanan
     document.getElementById('kategori').addEventListener('change', function () {
        const categoryId = this.value;

        // Fetch data berdasarkan service_category_id
        fetch(`/services?category=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data);
            })
            .catch(error => console.error('Error:', error));
    });

    function updateTable(services) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = ''; // Kosongkan tabel

        // Isi tabel dengan data baru
        services.forEach(service => {
            const row = `
                <tr>
                    <td class="border border-gray-300 p-2">${service.name}</td>
                    <td class="border border-gray-300 p-2 text-right">IDR ${service.price.toLocaleString()}</td>
                    <td class="border border-gray-300 p-2 text-center">
                        <input type="number" min="1" value="1" class="w-20 border p-1.5 border-gray-300 rounded-full text-center focus:outline-none focus:ring focus:ring-yellow-100">
                    </td>
                    <td class="border border-gray-300 p-2 text-center mt-6">
                        <button class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600" data-service-id="${service.id}">Tambah</button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    
    //menambahkan service ke ringkasan pemesanan
    document.addEventListener('DOMContentLoaded', () => {
        const ringkasanPesanan = document.querySelector('.ringkasan-pesanan');
        const tambahButtons = document.querySelectorAll('button.bg-blue-500');

        tambahButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const row = event.target.closest('tr');
                const namaItem = row.querySelector('td:nth-child(1)').innerText;
                const hargaItem = row.querySelector('td:nth-child(2)').innerText.replace('IDR ', '').replace(/,/g, '');
                const jumlahItem = row.querySelector('input').value;
                const serviceId = button.getAttribute('data-service-id'); // Ambil service_id

                if (button.innerText === "Tambah") {
                    // Tambahkan item ke ringkasan
                    const hargaTotal = parseInt(hargaItem) * parseInt(jumlahItem);

                    const li = document.createElement('li');
                    li.dataset.item = namaItem;
                    li.dataset.serviceId = serviceId;  // Simpan service_id di li
                    li.className = 'flex justify-between items-center border-b border-gray-300 pb-2 mb-2';
                    li.innerHTML = `
                        <span>${namaItem} x<span class="jumlah">${jumlahItem}</span></span>
                        <span class="font-semibold harga">IDR ${hargaTotal.toLocaleString()}</span>
                    `;
                    ringkasanPesanan.insertBefore(li, ringkasanPesanan.querySelector('.total-row'));

                    // Ubah tombol jadi Hapus
                    button.innerText = "Hapus";
                    button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    button.classList.add('bg-red-500', 'hover:bg-red-600');

                    // Update total harga
                    updateTotalHarga();
                } else {
                    // Hapus item dari ringkasan
                    const existingItem = [...ringkasanPesanan.querySelectorAll('li')]
                        .find(li => li.dataset.item === namaItem);

                    if (existingItem) {
                        existingItem.remove();
                    }

                    // Ubah tombol jadi Tambah
                    button.innerText = "Tambah";
                    button.classList.remove('bg-red-500', 'hover:bg-red-600');
                    button.classList.add('bg-blue-500', 'hover:bg-blue-600');

                    // Update total harga
                    updateTotalHarga();
                }
            });
        });

        function updateTotalHarga() {
            let total = 0;
            ringkasanPesanan.querySelectorAll('li').forEach(li => {
                const harga = li.querySelector('.harga')?.innerText.replace('IDR ', '').replace(/,/g, '');
                if (harga) total += parseInt(harga);
            });

            const totalRow = ringkasanPesanan.querySelector('.total-row .total-harga');
            totalRow.innerText = `IDR ${total.toLocaleString()}`;
        }
    });

    //menyimpan pesanan dan mengirimnya ke database
    document.querySelector('#simpanPesanan').addEventListener('click', () => {
        const ringkasanPesanan = document.querySelectorAll('.ringkasan-pesanan li:not(.total-row)');
        const reservationId = {{ $reservation->id }}; // Kirim ID reservasi
        const services = [];

        ringkasanPesanan.forEach(item => {
            const name = item.dataset.item;
            const quantity = item.querySelector('.jumlah').innerText;
            const price = item.querySelector('.harga').innerText.replace('IDR ', '').replace(/,/g, '');
            const serviceId = item.dataset.serviceId; // Ambil service_id dari li

            services.push({ name, quantity: parseInt(quantity), price: parseInt(price), service_id: serviceId });
        });

        fetch('{{ route('pesan-layanan.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                reservation_id: reservationId,
                services: services,
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => {
            console.error(error);
            alert('Gagal menyimpan pesanan. Cek log atau konsol browser untuk detail.');
        });
    });
</script>

@endsection
