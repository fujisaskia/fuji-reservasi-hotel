<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount(['rooms as available_rooms_count' => function ($query) {
            $query->where('room_status', 'tersedia');
        }])
        ->orderBy('available_rooms_count', 'desc') // Mengurutkan berdasarkan jumlah kamar tersedia, dari yang tertinggi ke yang terendah
        ->get();
        
        return view('user.offers', compact('roomTypes'));
    }
    

    public function showType($id)
    {
        // Ambil tipe kamar berdasarkan ID dan hitung kamar yang tersedia
        $roomType = RoomType::withCount(['rooms as available_rooms_count' => function ($query) {
            $query->where('room_status', 'tersedia');
        }])->findOrFail($id); // Gabungkan withCount langsung dengan findOrFail
        // Misalnya $roomType->fasilitas berisi string fasilitas
        $fasilitasArray = explode(',', $roomType->fasilitas);

        return view('user.booking', compact('roomType', 'fasilitasArray')); // Kirim data ke view
    }

    public function showBookings()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();
    
        // Mendapatkan data reservasi berdasarkan user yang login, diurutkan dari yang terbaru
        $reservations = Reservation::with(['payment', 'invoice']) // Memuat relasi payment dan invoice
            ->where('user_id', $user->id) // Filter berdasarkan user yang login
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan booking terbaru
            ->get();
    
        // Mengirimkan data ke view
        return view('user.my-booking', [
            'reservations' => $reservations,
        ]);
    }
      
    

    public function showBookingDetails($id)
    {
        // Mendapatkan data reservasi berdasarkan ID
        $reservation = Reservation::with(['payment', 'invoice'])
            ->where('id', $id)
            ->where('user_id', Auth::id()) // Memastikan hanya data milik user yang login yang bisa diakses
            ->firstOrFail();

        // Hitung apakah batas waktu pembatalan telah berakhir
        $canCancel = Carbon::now()->lessThan(
            Carbon::parse($reservation->check_in_date)->subDay()
        );

        // Mengirimkan data ke view detail-booking
        return view('user.booking-details', [
            'reservation' => $reservation,
            'canCancel' => $canCancel,
        ]);
    }

    public function showActiveDeposite()
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        
        // Mengambil semua reservasi dengan status 'checked-in' dan relasi invoice milik user yang sedang login
        $reservations = Reservation::where('reservation_status', 'checked-in') // Filter status checked-in
                                    ->where('user_id', $user->id) // Pastikan hanya milik user yang sedang login
                                    ->with('invoice', 'serviceOrders') // Ambil relasi invoice dan serviceOrders
                                    ->get();
        
        // Hitung total harga per reservasi
        $totalHarga = $reservations->sum(function ($reservation) {
            return $reservation->serviceOrders->sum('total_price'); // Menghitung total harga per layanan yang dipesan
        });
    
        // Deposit yang sudah dibayar dan kembalian/pembayaran tambahan
        $remainingDeposit = 0;
        $additionalPaymentRequired = 0;
    
        foreach ($reservations as $reservation) {
            // Mengambil invoice dari setiap reservasi
            $invoice = $reservation->invoice;
    
            // Pastikan invoice ada sebelum mengakses deposit
            if ($invoice) {
                $deposit = $invoice->deposit;
    
                // Hitung kembalian deposit atau pembayaran tambahan
                if ($totalHarga > $deposit) {
                    $additionalPaymentRequired = $totalHarga - $deposit; // Pembayaran tambahan
                } else {
                    $remainingDeposit = $deposit - $totalHarga; // Sisa deposit
                }
            }
        }
    
        return view('user.my-deposite', compact('reservations', 'totalHarga', 'remainingDeposit', 'additionalPaymentRequired'));
    }    

}
