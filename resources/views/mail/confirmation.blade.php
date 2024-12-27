<!DOCTYPE html>
<html>

<head>
    <title>Konfirmasi Reservasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 768px;
            background-color: #fff;
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 16px;
            margin: 40px auto;
            font-size: 14px;
        }

        .header {
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header img {
            width: 160px;
            display: block;
            margin: 0 auto;
        }

        .header p {
            margin: 8px 0;
            font-size: 14px;
        }

        .header .italic {
            font-style: italic;
        }

        .status {
            background-color: #22c55e;
            color: white;
            font-size: 17px;
            padding: 8px;
            margin-bottom: 32px;
            text-align: center;
        }

        .section-title {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 32px;
            margin: 20px 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            /* Agar teks dan isi sejajar vertikal */
            justify-content: space-between;
            /* Isi dan paragraf memiliki jarak */
            gap: 8px;
            /* Memberikan jarak antara paragraf dan isi */
            margin-top: 8px;
            padding-right: 10px;
        }

        .info-item p {
            margin: 0;
            /* Hilangkan margin default pada paragraf */
            flex-shrink: 0;
            /* Agar teks paragraf tidak mengecil */
        }

        .info-item span,
        .info-item a {
            text-align: left;
            /* Pastikan isi rata kiri */
            flex-grow: 1;
            /* Agar isi menyesuaikan ruang */
        }

        .room-section {
            margin: 24px 0;
        }

        .room-section h3 {
            font-weight: bold;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }

        .room-section p {
            font-size: 11px;
        }

        .room-section .font-semibold {
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .table th,
        .table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #374151;
            color: white;
        }

        .table td.text-right {
            text-align: right;
        }

        .table td.text-center {
            text-align: center;
        }

        .table .font-bold {
            font-weight: bold;
        }

        .policies {
            font-weight: bold;
            margin-top: 16px;
        }

        .policies li {
            font-size: 14px;
            padding: 8px;
            list-style: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div>
                <div>
                    <img src="{{ $hotelSetting->logo_path ? asset('storage/' . $hotelSetting->logo_path) : asset('assets/default-logo.png') }}" alt="{{ $hotelSetting->name ?? 'Default Logo' }}"> 
                </div>                
            </div>
            <div>
                <p>Yth kepada <span>{{ $reservation->user->full_name }}</span></p>
                <p>Terima kasih telah memilih {{ $hotelSetting->name }} untuk menginap mulai <span
                        class="italic">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('D d F, Y') }}</span>
                    to <span
                        class="italic">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('D d F, Y') }}</span>.
                    Kami berharap dapat membuat masa menginap Anda bersama kami menjadi nyaman dan menyenangkan.</p>
            </div>
        </div>

        <!-- Status -->
        <p class="status">booking telah di {{ $reservation->reservation_status }}</p>

        <!-- Reservation Summary -->
        <p class="section-title">Ringkasan Reservasi</p>
        <div class="grid">
            <!-- Guest Information -->
            <div>
                <p class="info-title">Informasi Tamu</p>
                <div>
                    <div class="info-item">
                        <p>Nama</p>
                        <span>{{ $reservation->user->full_name }}</span>
                    </div>
                    <div class="info-item">
                        <p>Email</p>
                        <a href="mailto:{{ $reservation->user->email }}">{{ $reservation->user->email }}</a>
                    </div>
                    <div class="info-item">
                        <p>Telepon</p>
                        <span>{{ $reservation->user->phone_number }}</span>
                    </div>
                </div>
            </div>

            <!-- Dates and Invoice -->
            <div>
                <div class="mb-3">
                    <p class="info-title">Tanggal</p>
                    <p>{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }} -
                        {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</p>
                </div>

                <div class="mb-3">
                    <p class="info-title">INVOICE</p>
                    <p class="font-semibold">{{ $reservation->invoice->invoice_number }}</p>
                </div>
            </div>
        </div>

        <!-- Room Information -->
        <div class="room-section">
            <h3>Hotel Yang Anda Pilih</h3>
            <div>
                <h2>{{ $hotelSetting->name }}</h2>
                <p>{{ $hotelSetting->address }}, <span>Phone {{ $hotelSetting->phone }}</span></p>
            </div>
            <p class="font-semibold">BIAYA KAMAR :</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Kamar</th>
                        <th class="text-right">Harga</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $reservation->roomType->tipe_kamar }}</td>
                        <td class="text-right">IDR {{ number_format($reservation->roomType->harga, 0, ',', ',') }}</td>
                        <td class="text-center">{{ $reservation->total_room }}</td>
                        <td class="text-right">IDR {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="font-bold">Total</td>
                        <td class="text-right font-bold">IDR
                            {{ number_format($reservation->total_price, 0, ',', ',') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="font-bold">Grand Total</td>
                        <td class="text-right font-bold">IDR
                            {{ number_format($reservation->payment->amount, 0, ',', ',') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Policies -->
        <div class="policies">
            <p>Kebijakan Tidak Dapat Dikembalikan:</p>
            <li>Jika Anda memilih untuk menjadwal ulang atau membatalkan pemesanan ini, Anda tidak akan menerima
                pengembalian uang apa pun</li>
        </div>
    </div>
</body>

</html>
