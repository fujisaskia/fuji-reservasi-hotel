<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
    <style>
        body {
            font-family: monospace;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 2rem auto;
            padding: 1.5rem;
            font-size: 12px;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
        }

        .filter {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .filter p {
            margin: 0;
            padding: 0.5rem;
        }

        .filter .highlight {
            border-radius: 4px;
            padding: 0.5rem;
            outline: none;
            box-shadow: 0 0 0 2px rgba(252, 211, 77, 0.5);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        thead {
            background-color: #d1d5db;
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        td.text-center {
            text-align: center;
        }

        td.text-right {
            text-align: right;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .empty {
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Tamu</h1>

        <!-- Tampilkan Filter Bulan -->
        <div class="filter">
            <p>Berdasarkan Bulan:</p>
            <p class="highlight">
                {{ $month ? \Carbon\Carbon::create()->month($month)->format('F') : 'Semua Bulan' }}
            </p>
        </div>

        <!-- Tabel -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Tamu</th>
                        <th>Invoice</th>
                        {{-- <th class="text-center">Nomor Kamar</th> --}}
                        <th class="text-center">Durasi Menginap</th>
                        <th>Tgl. Check-Out</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $reservation->user->full_name }}</td>
                        <td>{{ $reservation->invoice->invoice_number }}</td>
                        {{-- <td class="text-center">{{ $reservation->room->room_number }}</td> --}}
                        <td class="text-right">
                            {{ \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays($reservation->check_out_date) }} Malam
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
                        <td class="text-right">IDR {{ number_format($reservation->invoice->total_amount, 0, ',', ',') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty">Tidak ada data terkait.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
