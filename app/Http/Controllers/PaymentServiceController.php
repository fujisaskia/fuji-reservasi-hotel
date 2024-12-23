<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\PaymentService;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentServiceController extends Controller
{
    /**
     * Mendapatkan Snap Token untuk pembayaran layanan.
     */
    // public function getSnapToken(Request $request)
    // {
    //     try {
    //         // Validasi reservation_id
    //         $reservation = Reservation::findOrFail($request->reservation_id);

    //         // Ambil data invoice terkait dengan reservasi
    //         $invoice = $reservation->invoice;
        
    //         // Jika invoice tidak ditemukan, buat ID order tanpa nomor invoice
    //         $invoiceNumber = $invoice ? $invoice->invoice_number : 'INV-UNKNOWN';

    //         // Ambil service_orders dengan status 'unpaid'
    //         $unpaidServices = ServiceOrder::where('reservation_id', $reservation->id)
    //         ->where('status_order', 'unpaid')
    //         ->get();

    //         if ($unpaidServices->isEmpty()) {
    //             return response()->json([
    //                 'message' => 'Tidak ada layanan yang perlu dibayar.'
    //             ], 400);
    //         }

    //         // Hitung total_price dari service_orders 'unpaid'
    //         $grossAmount = $unpaidServices->sum('total_price');

    //         // Pastikan jumlah tidak negatif atau nol
    //         if ($grossAmount <= 0) {
    //             return response()->json([
    //                 'message' => 'Tidak ada layanan yang dipesan untuk pembayaran.'
    //             ], 400);
    //         }

    //         $orderId = 'ByHotel/SERVICE-' . $invoiceNumber . '-' . uniqid();

    //         // Konfigurasi Midtrans
    //         Config::$serverKey = config('midtrans.server_key');
    //         Config::$isProduction = config('midtrans.is_production');
    //         Config::$isSanitized = true;
    //         Config::$is3ds = true;

    //         // Data untuk Snap
    //         $params = [
    //             'transaction_details' => [
    //                 'order_id' => $orderId,
    //                 'gross_amount' => $grossAmount,
    //             ],
    //             'customer_details' => [
    //                 'first_name' => $reservation->user->full_name   ,
    //                 'email' => $reservation->user->email,
    //                 'phone' => $reservation->user->phone_number,
    //             ],
    //         ];

    //         $snapToken = Snap::getSnapToken($params);

    //         PaymentService::create([
    //             'reservation_id' => $reservation->id,
    //             'order_id' => $orderId,
    //             'amount' => $grossAmount,
    //             'payment_status' => 'pending',
    //             'payment_method' => 'snap_midtrans',
    //             'payment_date' => now(),
    //         ]);

    //         return response()->json(['snapToken' => $snapToken]);

    //     } catch (\Exception $e) {
    //         Log::error('Terjadi kesalahan saat mendapatkan Snap Token', [
    //             'error' => $e->getMessage(),
    //             'stack' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json([
    //             'message' => 'Gagal mendapatkan Snap Token. Silakan coba lagi.',
    //         ], 500);
    //     }
    // }

    /**
     * Memperbarui status pembayaran layanan.
     */
    // public function updatePaymentStatus(Request $request)
    // {
    //     try {
    //         Log::info('Request masuk ke updatePaymentStatus', $request->all());

    //         $orderId = $request->input('order_id');
    //         $paymentStatus = $request->input('payment_status');

    //         if (!$orderId || !$paymentStatus) {
    //             return response()->json([
    //                 'message' => 'Parameter order_id atau payment_status tidak valid.',
    //             ], 400);
    //         }

    //         $payment = PaymentService::where('order_id', $orderId)->first();

    //         if (!$payment) {
    //             Log::error('Transaksi tidak ditemukan', ['order_id' => $orderId]);
    //             return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
    //         }

    //         if ($paymentStatus === 'success') {
    //             $payment->payment_status = 'success';
    //             $payment->save();

    //             // Update status_order service orders menjadi 'paid'
    //             ServiceOrder::where('reservation_id', $payment->reservation_id)
    //                 ->where('status_order', 'unpaid')
    //                 ->update(['status_order' => 'paid']);

    //             Log::info('Status pembayaran berhasil diperbarui', ['order_id' => $orderId]);

    //             return response()->json([
    //                 'message' => 'Status pembayaran berhasil diperbarui.',
    //                 'payment' => $payment,
    //             ]);
    //         }

    //         Log::warning('Status pembayaran tidak valid', $request->all());
    //         return response()->json([
    //             'message' => 'Status pembayaran tidak valid atau tidak diperbarui.',
    //         ], 400);

    //     } catch (\Exception $e) {
    //         Log::error('Terjadi kesalahan saat memperbarui status pembayaran', [
    //             'error' => $e->getMessage(),
    //             'stack' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json([
    //             'message' => 'Gagal memperbarui status pembayaran. Silakan coba lagi.',
    //         ], 500);
    //     }
    // }

}