<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Layanan</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center; /* Center content vertically */
            height: 100vh;
            width: 100vw;
            box-sizing: border-box;
            padding: 20px;
            overflow: hidden; /* Prevent scrolling */
        }
        .receipt-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 95%; /* Full-width but within a 95% limit */
            max-width: 900px; /* Adjust for a more centered look */
            height: 100%; /* Full height of the container */
            box-sizing: border-box;
            overflow: auto; /* Allow scrolling if content is too long */
        }
        h2 {
            font-size: 1.75rem; /* Larger title */
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid #333;
            margin: 0;
        }
        .info-section {
            padding: 10px 0;
            border-bottom: 1px dashed #ddd;
        }
        .info-section p {
            margin: 5px 0;
            font-size: 1rem; /* Slightly larger text */
        }
        .info-section strong {
            display: block;
            margin-bottom: 2px;
        }
        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0; /* Space between sections */
        }
        .table-container th,
        .table-container td {
            border: 1px solid #ddd;
            padding: 8px; /* More padding for readability */
            text-align: center;
            font-size: 1rem; /* Larger text */
        }
        .table-container th {
            background-color: #f0f0f0;
            text-transform: uppercase;
        }
        .table-container td:nth-child(2) {
            text-align: left;
            padding-left: 10px;
        }
        .table-container td:nth-child(3),
        .table-container td:nth-child(5) {
            text-align: right;
            padding-right: 10px;
        }
        .footer {
            padding: 10px 0;
            border-top: 2px solid #333;
            text-align: right;
            font-weight: bold;
        }
        .footer span {
            display: block;
        }
        .text-small {
            font-size: 0.875rem; /* Smaller date text */
            color: #555;
        }
    </style>
</head>
<body>
    @foreach ($reservations as $reservation)
        <div class="receipt-container">
            <h2>Invoice Layanan</h2>
            <div class="info-section">
                <p><strong>Reservasi ID:</strong> {{ $reservation->invoice->invoice_number }}</p>
                {{-- <p><strong>Nomor Kamar:</strong> {{ $reservation->roomType->tipe_kamar }} - {{ $reservation->room->first()->room_number }}</p> --}}
                <p><strong>Nama Tamu:</strong> {{ $reservation->user->full_name }}</p>
            </div>

            <table class="table-container">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Item</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $reservationServices = $services->where('reservation_id', $reservation->id);
                    @endphp
                    @forelse ($reservationServices as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $service->service->name ?? 'N/A' }}
                                <div class="text-small">{{ \Carbon\Carbon::parse($service->service_date)->format('M d, Y') }}</div>
                            </td>
                            <td>Rp {{ number_format($service->service->price, 0, ',', ',') }}</td>
                            <td>Rp {{ number_format($service->total_price, 0, ',', ',') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada layanan yang dipesan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer">
                <span>Total Harga</span>
                <span>Rp {{ number_format($reservationServices->sum('total_price'), 0, ',', ',') }}</span>
            </div>
        </div>
    @endforeach
</body>
</html>
