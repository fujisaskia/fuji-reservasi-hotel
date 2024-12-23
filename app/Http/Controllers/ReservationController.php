<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ReservationCancelledMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str; // Untuk generate invoice_number yang unik

class ReservationController extends Controller
{

    /**
     * Simpan data pemesanan kamar user.
     */
    public function store(Request $request, $roomTypeId)
    {
        // Validasi data input
        $validated = $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_room' => 'required|integer|min:1',
            'total_guest' => 'required|integer|min:1',
        ]);

        // Ambil data RoomType berdasarkan ID dari URL
        $roomType = RoomType::findOrFail($roomTypeId);

        // Validasi ketersediaan kamar
        $availableRooms = DB::table('rooms')
            ->where('room_type_id', $roomTypeId)
            ->where('room_status', 'tersedia')
            ->whereNotExists(function ($query) use ($validated) {
                $query->select(DB::raw(1))
                    ->from('reservations')
                    ->whereRaw('rooms.id = reservations.room_type_id')  // Pastikan Anda menggunakan kolom yang tepat
                    ->where(function ($q) use ($validated) {
                        $q->whereBetween('check_in_date', [$validated['check_in_date'], $validated['check_out_date']])
                        ->orWhereBetween('check_out_date', [$validated['check_in_date'], $validated['check_out_date']])
                        ->orWhere(function ($subQuery) use ($validated) {
                            $subQuery->where('check_in_date', '<=', $validated['check_in_date'])
                                    ->where('check_out_date', '>=', $validated['check_out_date']);
                        });
                    });
            })
            ->count();

        if ($availableRooms < $validated['total_room']) {
            return redirect()->route('user.booking', ['id' => $roomTypeId])
                            ->with('error', 'Kamar ini tidak tersedia di tanggal tersebut.');
        }

        // Hitung jumlah malam
        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);

        // Hitung total harga
        $pricePerNight = $roomType->harga;
        $totalPrice = $pricePerNight * $validated['total_room'] * $nights;

        // Simpan data pemesanan ke database
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'room_type_id' => $roomType->id, // Pastikan menggunakan room_type_id, bukan room_id
            'total_price' => $totalPrice,
            'total_room' => $validated['total_room'],
            'total_guest' => $validated['total_guest'],
            'reservation_date' => now(),
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'reservation_status' => 'Pending'
        ]);

        // Generate nomor invoice unik
        $invoiceNumber = 'INV-' . strtoupper(Str::random(3)) . rand(100, 999);

        // Simpan data invoice ke database
        $invoiceNumber = Invoice::create([
            'reservation_id' => $reservation->id,
            'invoice_number' => $invoiceNumber,
            'total_amount' => $totalPrice,
            'invoice_date' => now(),
            'due_date' => now()->addDays(1),
        ]);

        // Redirect ke halaman konfirmasi dengan data pemesanan
        return redirect()->route('reservations.confirmation', $reservation->id)
                        ->with('success', 'Pemesanan berhasil disimpan dengan nomor invoice: ' . $invoiceNumber);
    }

    


    /**
     * Tampilkan halaman konfirmasi pemesanan user.
     */    
    public function confirmation($id)
    {
        $reservation = Reservation::with('roomType')->findOrFail($id);
        return view('user.confirmation', compact('reservation'));
    }
    

    /**
     * Update data reservasi user.
     */
    public function update(Request $request, $reservationId)
    {
        // Validasi data input
        $validated = $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_room' => 'required|integer|min:1',
            'total_guest' => 'required|integer|min:1',
        ]);
    
        // Cari data reservasi berdasarkan ID
        $reservation = Reservation::findOrFail($reservationId);
    
        // Ambil data RoomType berdasarkan room_type_id di reservasi
        $roomType = RoomType::findOrFail($reservation->room_type_id);
    
        // Hitung jumlah malam
        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
    
        // Hitung total harga baru
        $pricePerNight = $roomType->harga;
        $totalPrice = $pricePerNight * $validated['total_room'] * $nights;
    
        // Update data reservasi
        $reservation->update([
            'total_price' => $totalPrice,
            'total_room' => $validated['total_room'],
            'total_guest' => $validated['total_guest'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('reservations.confirmation', $reservation->id)
            ->with('success', 'Data reservasi berhasil diperbarui!');
    }

    /**
     * delete data reservasi user.
     */
    public function cancel($id)
    {
        // Temukan reservasi berdasarkan ID
        $reservation = Reservation::findOrFail($id);
    
        // Simpan roomTypeId untuk redirect
        $roomTypeId = $reservation->room_type_id;
    
        // Hapus data reservasi
        $reservation->delete();
    
        // Redirect ke halaman form dengan parameter roomTypeId
        return redirect()->route('user.booking', ['id' => $roomTypeId])
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    
    /**
     * batalkan reservasi oleh tamu
     */
    public function cancelReservationByGuest($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Periksa apakah masih bisa dibatalkan
        if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse($reservation->check_in_date)->subDay())) {
            return redirect()->back()->with('error', 'Batas waktu pembatalan telah berakhir.');
        }

        // Ubah status menjadi 'cancelled'
        $reservation->reservation_status = 'Cancelled';
        $reservation->save();

        Mail::to($reservation->user->email)->send(new ReservationCancelledMail($reservation));

        return redirect()->back()->with('success', 'Reservasi berhasil dibatalkan.');
    }
    
}

