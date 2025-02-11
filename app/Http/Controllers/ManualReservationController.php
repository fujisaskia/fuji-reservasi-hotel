<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Invoice;
use App\Models\RoomType;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk generate invoice_number yang unik


class ManualReservationController extends Controller
{

    public function showCreateReservationPage()
    {
        $users = User::all(['id', 'full_name', 'email']); // Ambil nama dan email user
        
        $availableRoom = RoomType::whereHas('rooms', function ($query) {
            $query->where('room_status', 'tersedia');
        })->get();

        return view('receptionist.create-reservation', compact('users', 'availableRoom'));
    }


    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_type_id' => 'required|exists:room_types,id',
            'total_room' => 'required|integer|min:1',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_guest' => 'required|integer|min:1',
        ]);

        // Konversi tanggal ke format yang diinginkan menggunakan Carbon
        $checkInDate = Carbon::createFromFormat('d M Y', $request->check_in_date)->format('Y-m-d');
        $checkOutDate = Carbon::createFromFormat('d M Y', $request->check_out_date)->format('Y-m-d');
    
        // Hitung jumlah malam
        $checkIn = Carbon::parse($request['check_in_date']);
        $checkOut = Carbon::parse($request['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
    
        // Ambil data RoomType berdasarkan ID dari URL
        $roomType = RoomType::findOrFail($request->room_type_id);
    
        // Hitung total harga
        $pricePerNight = $roomType->harga;
        $totalPrice = $pricePerNight * $request['total_room'] * $nights;
    
        // Create reservation
        $reservation = Reservation::create([
            'user_id' => $request->user_id,
            'room_type_id' => $request->room_type_id,
            'total_price' => $totalPrice,
            'total_room' => $request->total_room,
            'reservation_date' => now(),
            'check_in_date' => $checkInDate, // Gunakan tanggal yang sudah dikonversi
            'check_out_date' => $checkOutDate, // Gunakan tanggal yang sudah dikonversi
            'total_guest' => $request->total_guest,
            'reservation_status' => 'Pending'
        ]);

        // Generate nomor invoice unik
        $invoiceNumber = 'INH-' . strtoupper(Str::random(3)) . rand(100, 999);

        // Simpan data invoice ke database
        $invoiceNumber = Invoice::create([
            'reservation_id' => $reservation->id,
            'invoice_number' => $invoiceNumber,
            'total_amount' => $totalPrice,
            'invoice_date' => now(),
            'due_date' => now()->addDays(1),
        ]);

        // Ambil data lengkap dengan relasi
        $reservationData = Reservation::with(['user', 'roomType'])
        ->where('id', $reservation->id)
        ->first();

         // Format tanggal dan harga
        $reservationData->check_in_date = \Carbon\Carbon::parse($reservationData->check_in_date)->translatedFormat('d F Y');
        $reservationData->check_out_date = \Carbon\Carbon::parse($reservationData->check_out_date)->translatedFormat('d F Y');
        $reservationData->total_price = number_format($reservationData->total_price, 0, ',', ',');
    
        // Return response
        if ($reservation) {
            return response()->json([
                'success' => true,
                'data' => $reservationData,
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function processCashPayment(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);

        $invoice = $reservation->invoice;
        $invoiceNumber = $invoice ? $invoice->invoice_number : 'INV-UNKNOWN';

        // Simpan pembayaran langsung dengan status success
        Payment::create([
            'reservation_id' => $reservation->id,
            'order_id' => 'CASH-' . $invoiceNumber . '-' . uniqid(),
            'amount' => $reservation->total_price,
            'payment_status' => 'success',
            'payment_method' => 'cash',
            'payment_date' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function getSnapToken(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);

        $invoice = $reservation->invoice;
        $invoiceNumber = $invoice ? $invoice->invoice_number : 'INV-UNKNOWN';

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'ByHotel/BOOKING-byHotel-' . $invoiceNumber . '-' . uniqid();
        $grossAmount = $reservation->total_price;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $reservation->user->full_name,
                'email' => $reservation->user->email,
                'phone' => $reservation->user->phone_number,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        Payment::create([
            'reservation_id' => $reservation->id,
            'order_id' => $orderId,
            'amount' => $grossAmount,
            'payment_status' => 'pending',
            'payment_method' => 'digital_byHotel',
            'payment_date' => now(),
        ]);

        return response()->json(['snapToken' => $snapToken]);
    }

    public function updatePaymentStatusReservation(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'payment_status' => 'required|string|in:success',
        ]);

        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 404);
        }

        $payment->update(['payment_status' => $request->payment_status]);

        return response()->json(['message' => 'Pembayaran Online Berhasil!.'], 200);
    }


    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Membuat Reservasi berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan reservasi.'
            ]);
        }
    }



    // Menampilkan form tambah pengguna
    public function createAccountForGuest()
    {
        return view('receptionist.create-account');
    }

    public function createGuestAccount(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:15',
            'identification_type' => 'required|string',
            'identification_number' => 'required|string|unique:users,identification_number',
        ]);

        try {
            // Create the user
            $user = User::create([
                'title' => $request->title,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make('password123'), // Default password
                'phone_number' => $request->phone_number,
                'identification_type' => $request->identification_type,
                'identification_number' => $request->identification_number,
                'role' => 'user', // Default role
            ]);

            return redirect()->back()->with('sweetalert', [
                'type' => 'success',
                'message' => 'Akun tamu berhasil dibuat!',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('sweetalert', [
                'type' => 'error',
                'message' => 'Gagal membuat akun tamu. Silakan coba lagi.',
            ]);
        }
    }

        
 
}
