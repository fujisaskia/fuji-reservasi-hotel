{{-- <h1>Checkout Berhasil</h1>
<p>Halo, {{ $reservation->user->full_name }}</p>
<p>Reservasi dengan kode invoice {{ $reservation->invoice->invoice_number }} telah berhasil di-checkout.</p>
<p>Total biaya layanan: Rp{{ number_format($reservation->serviceOrders()->sum('total_price'), 0, ',', '.') }}</p>
<p>Terima kasih telah menginap di Hotel Ruby!</p> --}}

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Rincian Pembayaran Reservasi</title>
</head>
<body>
    <h2>Rincian Pembayaran Reservasi</h2>
    <p><strong>Nama Kamar:</strong> {{ $room->name }} (Tipe: {{ $room->roomType->name }})</p>
    <p><strong>Durasi Menginap:</strong> {{ $nights }} Malam</p>
    <p><strong>Harga per Malam:</strong> Rp{{ number_format($roomPricePerNight, 2) }}</p>

    <h3>Rincian Pembayaran</h3>
    <p><strong>Total Pembayaran Kamar:</strong> Rp{{ number_format($roomPaymentTotal, 2) }}</p>
    <p><strong>Total Biaya Service Orders:</strong> Rp{{ number_format($serviceOrderTotal, 2) }}</p>
    <p><strong>Total Pembayaran:</strong> Rp{{ number_format($grandTotal, 2) }}</p>

    <h3>Deposit</h3>
    <p><strong>Deposit yang Dibayar:</strong> Rp{{ number_format($deposit, 2) }}</p>
    @if ($remainingDeposit > 0)
        <p><strong>Deposit yang Tersisa:</strong> Rp{{ number_format($remainingDeposit, 2) }}</p>
    @else
        <p><strong>Jumlah Pembayaran Tambahan Diperlukan:</strong> Rp{{ number_format($additionalPaymentRequired, 2) }}</p>
    @endif
</body>
</html> --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        .invoice-container {
            max-width: 36rem;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            font-size: 0.875rem;
        }

        .invoice-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-title {
            text-transform: uppercase;
            font-size: 1.125rem;
        }

        .invoice-number {
            font-family: monospace;
            font-size: 1.25rem;
        }

        .room-details {
            background-color: #fefce8;
            padding: 1rem;
            border-radius: 0.375rem;
            border: 1px solid #fde047;
            margin-bottom: 1.25rem;
        }

        .room-type {
            font-size: 1rem;
            font-weight: bold;
            color: #881337;
            margin-bottom: 0.5rem;
        }

        .room-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .room-price .price {
            font-weight: bold;
        }

        .room-quantity {
            font-size: 0.625rem;
        }

        .total-price {
            font-weight: bold;
            color: #9f1239;
        }

        .guest-info {
            display: flex;
            justify-content: space-between;
        }

        .date-info {
            margin-bottom: 1rem;
        }

        .date-title {
            font-weight: 600;
        }

        .date-range {
            display: flex;
        }

        .guest-information {
            margin-bottom: 0.5rem;
        }

        .guest-label {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .guest-name, .guest-email {
            display: flex;
            gap: 0.25rem;
        }

        .deposit-info {
            display: flex;
            justify-content: space-between;
            background-color: #f3f4f6;
            padding: 0.75rem;
            border-radius: 0.375rem;
        }

        .deposit-label {
            font-weight: 600;
            color: #111827;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 1rem auto;
            width: 100%;
        }

        .bill-details-title {
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1rem;
        }

        .bill-table {
            width: 100%;
            text-align: left;
        }

        .table-header {
            background-color: #e5e7eb;
        }

        .table-header th {
            padding: 0.75rem;
        }

        .room-separator, .service-separator {
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #4b5563;
        }

        .small-text {
            font-size: 0.6875rem;
        }

        .text-right {
            text-align: right;
        }

        .total-service, .total-deposit, .additional-payment, .remaining-deposit {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }

        .additional-payment {
            color: #dc2626;
            background-color: #e5e7eb;
        }

        .remaining-deposit {
            color: #16a34a;
            background-color: #e5e7eb;
        }

        .notes {
            text-align: center;
            padding: 0 1rem;
            margin-top: 1.25rem;
        }

        .notes-title {
            font-weight: 600;
            color: #111827;
            font-size: 0.875rem;
        }

        .notes-content {
            font-size: 0.625rem;
        }
    </style>
    <body>  
        <div class="invoice-container">
            <div class="invoice-header">
                <h2 class="invoice-title">INVOICE</h2>
                <span class="invoice-number">{{ $reservation->invoice->invoice_number }}</span>
            </div>
            <div class="invoice-content">
                <div class="room-details">
                    <h3 class="room-type">{{ $room->roomType->tipe_kamar }}</h3>
                    <div class="room-info">
                        <div class="room-price">
                            <p class="price">IDR {{ number_format($room->roomType->harga, 0, ',', ',') }}</p>
                            <p class="room-quantity"><span id="total-room">{{ $reservation->total_room }}</span> Kamar x <span id="total-nights">{{ $nights }}</span> Malam</p>
                        </div>
                        <p class="total-price">IDR <span id="total-price">{{ number_format($reservation->total_price, 0, ',', ',') }}</span></p>
                    </div>
                    <div class="guest-info">
                        <p>Max. Tamu:</p>
                        <span class="total-guest"><span id="total-guest">{{ $reservation->total_guest }}</span> Orang</span>
                    </div>
                </div>
    
                <div class="date-info">
                    <h4 class="date-title">Tanggal :</h4>
                    <div class="date-range">
                        <p id="checkin-date">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('F d, Y') }}</p>
                    </div>
                </div>
    
                <div class="guest-information">
                    <label for="nama-tamu" class="guest-label">Informasi Tamu</label>
                    <div class="guest-name">
                        <p id="salutation">{{ $reservation->user->title }}</p>
                        <p id="first-name">{{ $reservation->user->full_name }}</p>
                    </div>
                    <div class="guest-email">
                        <p id="email">{{ $reservation->user->email }}</p>
                    </div>
                </div>
    
                <div class="deposit-info">
                    <label for="deposit" class="deposit-label">DEPOSIT</label>
                    <p id="deposit">{{ number_format($reservation->invoice->deposit ?? 0, 0, ',', ',') }}</p>
                </div>
            </div>
    
            <div class="divider"></div>
    
            <h1 class="bill-details-title">- Rincian Tagihan -</h1>
    
            <div class="bill-details">
                <table class="bill-table">
                    <thead>
                        <tr class="table-header">
                            <th>Keterangan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="room-separator">
                            <td colspan="4">Kamar</td>
                        </tr>
                        <tr>
                            <td>{{ $reservation->roomType->tipe_kamar }}</td>
                            <td>IDR {{ number_format($roomPricePerNight, '0', ',', ',') }}</td>
                            <td>{{ $reservation->total_room }} <span class="small-text">Kamar</span> x {{ $nights }} <span class="small-text">Malam</span></td>
                            <td class="text-right">IDR {{ number_format($reservation->payment->amount, '0', ',', ',') }} <span class="small-text">Lunas</span></td>
                        </tr>
    
                        <tr class="service-separator">
                            <td colspan="4">Layanan</td>
                        </tr>
                        @forelse ($reservation->serviceOrders as $order)
                            <tr>
                                <td>{{ $order->service->name }}</td>
                                <td>IDR {{ number_format($order->price, '0', ',', ',') }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td class="text-right">IDR {{ number_format($order->total_price) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="no-service">Tidak ada layanan yang dipesan</td>
                            </tr>
                        @endforelse
    
                        <tr class="total-service">
                            <td colspan="3">Total Layanan :</td>
                            <td class="text-right">IDR {{ number_format($serviceOrderTotal, '0', ',', ',') }}</td>
                        </tr>
    
                        <tr class="total-deposit">
                            <td colspan="3">Deposit :</td>
                            <td class="text-right">IDR {{ number_format($deposit, '0', ',', ',') }}</td>
                        </tr>
    
                        @if ($additionalPaymentRequired > 0)
                            <tr class="additional-payment">
                                <td colspan="3">Tambahan Pembayaran</td>
                                <td class="text-right">IDR {{ number_format($additionalPaymentRequired, '0', ',', ',') }}</td>
                            </tr>
                        @elseif ($remainingDeposit > 0)
                            <tr class="remaining-deposit">
                                <td colspan="3">Kembalian Deposit</td>
                                <td class="text-right">IDR {{ number_format($remainingDeposit, '0', ',', ',') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    
            <div class="notes">
                <h3 class="notes-title">- Catatan -</h3>
                <q class="notes-content">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quod quaerat sapiente, doloremque amet delectus facilis quisquam fuga soluta consectetur voluptates possimus excepturi non ad necessitatibus id, aliquam maiores dignissimos.</q>
            </div>
        </div>
    </body>
</html>
