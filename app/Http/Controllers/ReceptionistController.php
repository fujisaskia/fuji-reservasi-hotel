<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ReservationConfirmationMail;
use Illuminate\Support\Facades\Mail;


class ReceptionistController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['invoice', 'payment'])
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'success');
            })
            // kecualikan reservasi yang statusnya 'Checked-Out'
            ->where('reservation_status', '!=', 'Checked-Out');
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('reservation_status', $request->status);
        }
    
        // Pencarian berdasarkan nama atau nomor invoice
        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('full_name', 'like', '%' . $request->search . '%');
                })->orWhereHas('invoice', function ($invoiceQuery) use ($request) {
                    $invoiceQuery->where('invoice_number', 'like', '%' . $request->search . '%');
                });
            });
        }
    
        // Urutkan berdasarkan status "Checked-Out" di bawah
        $reservations = $query->orderByRaw("CASE WHEN reservation_status = 'Checked-Out' THEN 1 ELSE 0 END")
            ->paginate(20);
    
        return view('receptionist.reservasi', compact('reservations'));
    }
    
    
    
    //resepsionist mengonfirmasi reservasi user   
    public function confirmReservation($id)
    {
        $reservation = Reservation::with(['user', 'roomType', 'invoice', 'payment'])->findOrFail($id);
    
        if ($reservation->reservation_status === 'Pending') {
            $reservation->reservation_status = 'Confirmed';
            $reservation->save();
    
            // Kirim email konfirmasi
            if ($reservation->user) {
                Mail::to($reservation->user->email)->send(new ReservationConfirmationMail($reservation));
            }
    
            $userName = $reservation->user ? $reservation->user->full_name : 'Nama user tidak tersedia';
    
            return redirect()->back()->with('status', 'Reservasi oleh <strong>' . $userName . '</strong> berhasil dikonfirmasi!');
        } else {
            return redirect()->back()->with('error', 'Reservasi tidak dapat dikonfirmasi, mohon periksa status reservasi terlebih dahulu.');
        }
    }

    
    //menampilkan data kamar yang "tersedia" di fitur check-in
    public function showAvailableRooms()
    {
        $availableRooms = Room::with('roomType')->where('room_status', 'tersedia')->get();
        return view('receptionist.check-in', compact('availableRooms'));
    }

    //mengirim data untuk form check-in
    public function showCheckInForm($id)
    {
        // Ambil detail kamar berdasarkan ID
        $room = Room::with('roomType')->findOrFail($id);
    
        // Ambil reservasi yang pending
        $reservations = Reservation::where('reservation_status', 'Confirmed')->get();
        $invoices = Invoice::whereIn('reservation_id', $reservations->pluck('id'))->get();
    
        // Tampilkan form check-in dengan data kamar dan reservasi
        return view('receptionist.in-room', compact('room', 'reservations', 'invoices'));
    }

    public function processCheckIn(Request $request, $roomId)
    {
        // Validasi input
        $validatedData = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'room_id' => 'required|exists:rooms,id',
            'deposit' => 'nullable|numeric|min:0',
        ]);
    
        // Ambil data kamar berdasarkan ID
        $room = Room::findOrFail($roomId);
    
        // Periksa apakah kamar tersedia
        if ($room->room_status !== 'tersedia') {
            return redirect()->back()->withErrors(['error' => 'Kamar tidak tersedia untuk check-in.']);
        }
    
        // Ambil reservasi berdasarkan ID
        $reservation = Reservation::findOrFail($validatedData['reservation_id']);
    
        // Periksa apakah reservasi valid dan statusnya "Confirmed"
        if ($reservation->reservation_status !== 'Confirmed') {
            return redirect()->back()->withErrors(['error' => 'Reservasi tidak valid atau sudah di-check-in.']);
        }
    
        // Periksa apakah kamar yang dipilih sesuai dengan tipe kamar yang dipesan
        if ($room->room_type !== $reservation->room_type) {
            return redirect()->back()->withErrors(['error' => 'Tipe kamar tidak sesuai dengan reservasi.']);
        }
    
        // Proses check-in dengan menambahkan deposit
        try {
            DB::transaction(function () use ($room, $reservation, $validatedData) {
                // Tambahkan kamar ke tabel pivot
                $reservation->room()->attach($room->id);
    
                // Update status kamar menjadi 'terisi'
                $room->update(['room_status' => 'terisi']);
    
                // Hitung jumlah kamar yang sudah dipilih di pivot
                $checkedInRooms = $reservation->room()->count();
    
                // Jika jumlah kamar yang dipilih di pivot sudah sama dengan total_rooms
                if ($checkedInRooms == $reservation->total_room) {
                    // Ubah status reservasi menjadi 'Checked-In'
                    $reservation->update(['reservation_status' => 'Checked-In']);
                }
    
                // Update kolom deposit di tabel invoices
                Invoice::where('reservation_id', $reservation->id)->update([
                    'deposit' => $validatedData['deposit'],
                ]);
            });
    
            // Redirect dengan pesan sukses
            return redirect()->route('receptionist.dashboard')->with('success', 'Check-in berhasil dan deposit telah ditambahkan!');
        } catch (\Exception $e) {
            // Tangani error jika ada kegagalan dalam proses transaksi
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses check-in. Silakan coba lagi.']);
        }
    }
    
    

    //menampilkan data kamar yang "terisi" di fitur check-in
    public function showOccupiedRooms()
    {
        // Mengambil kamar yang berstatus "terisi"
        $occupiedRooms = Room::with('roomType')->where('room_status', 'terisi')->get();

        // Passing data ke view
        return view('receptionist.check-out', compact('occupiedRooms'));
    }    

    public function showCheckOutForm($id)
    {
        // Ambil detail kamar berdasarkan ID
        $room = Room::with('roomType')->findOrFail($id);
    
        // Ambil data reservasi aktif berdasarkan ID kamar menggunakan relasi
        $reservation = Reservation::with(['serviceOrders', 'payment', 'invoice'])
            ->whereHas('room', function($query) use ($id) {
                $query->where('rooms.id', $id); // Menyesuaikan dengan ID kamar
            })
            ->where('reservation_status', '!=', 'Checked-Out') // Pastikan reservasi belum check-out
            ->firstOrFail();
    
        // Hitung selisih hari antara check-in dan check-out
        $checkInDate = Carbon::parse($reservation->check_in_date);
        $checkOutDate = Carbon::parse($reservation->check_out_date);
        $nights = $checkInDate->diffInDays($checkOutDate);

        // Menghitung harga per malam untuk kamar
        $totalRoom = $reservation->total_room; // Asumsi ada total_room
        $roomPricePerNight = $reservation->payment->amount / ($nights * $totalRoom);
    
        // Hitung total pembayaran reservasi kamar
        $roomPaymentTotal = $reservation->payment->amount;
    
        // Hitung total biaya service orders
        $serviceOrderTotal = $reservation->serviceOrders->sum('total_price');
    
        // Hitung total keseluruhan
        $grandTotal = $roomPaymentTotal + $serviceOrderTotal;
    
        // Deposit yang sudah dibayar
        $deposit = $reservation->invoice->deposit;
    
        // Hitung kembalian deposit atau pembayaran tambahan
        if ($serviceOrderTotal > $deposit) {
            $additionalPaymentRequired = $serviceOrderTotal - $deposit;
            $remainingDeposit = 0; // Deposit tidak cukup, perlu pembayaran tambahan
        } else {
            $remainingDeposit = $deposit - $serviceOrderTotal;
            $additionalPaymentRequired = 0; // Tidak ada pembayaran tambahan, deposit cukup
        }
    
        // Kirim data ke view
        return view('receptionist.out-room', compact('room', 'reservation', 'roomPaymentTotal', 'serviceOrderTotal', 'grandTotal', 'nights', 'deposit', 'remainingDeposit', 'additionalPaymentRequired', 'roomPricePerNight'));
    }
    
    
    public function processCheckOut($id)
    {
        DB::beginTransaction(); // Mulai transaksi atomik
    
        try {
            // Ambil reservasi berdasarkan ID dengan kamar terkait
            $reservation = Reservation::with(['room', 'serviceOrders', 'invoice'])->findOrFail($id);
    
            // Validasi apakah status reservasi sudah Checked-Out
            if ($reservation->reservation_status === 'Checked-Out') {
                return redirect()->back()->with('error', 'Reservasi ini sudah dalam status Checked-Out.');
            }
    
            // Periksa semua kamar yang terkait dengan reservasi
            $rooms = $reservation->room; // Ambil semua kamar melalui relasi many-to-many
            if ($rooms->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ditemukan kamar yang terkait dengan reservasi ini.');
            }
    
            // Loop untuk mengubah semua kamar menjadi "perawatan"
            foreach ($rooms as $room) {
                $room->update(['room_status' => 'perawatan']);
            }
    
            // Ubah status reservasi menjadi "Checked-Out"
            $reservation->update(['reservation_status' => 'Checked-Out']);
    
            // Hitung total biaya layanan yang berstatus "paid"
            $serviceOrderTotal = $reservation->serviceOrders()
                ->sum('total_price');

            // Tambahkan total biaya layanan ke kolom total_amount pada tabel invoice
            $invoice = $reservation->invoice;
            if ($invoice) {
                $invoice->update([
                    'total_amount' => $invoice->total_amount + $serviceOrderTotal,
                ]);
            }

            DB::commit(); // Simpan semua perubahan
            return redirect()->route('check-out.index')->with('success', 'Semua kamar dalam reservasi berhasil di-check-out.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses check-out: ' . $e->getMessage());
        }
    }
    

    //menampilkan user yang sudah check-in ke fitur tamu receptionist
    public function showCheckedInReservations(Request $request)
    {
        // Ambil query pencarian dari input search
        $search = $request->input('search');
    
        // Query untuk mendapatkan data reservasi dengan status 'checked-in'
        $reservations = Reservation::where('reservation_status', 'Checked-In')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%');
                });
            })
            ->with('room', 'user') // Asumsi ada relasi user dan room
            ->get();
    
        // Kirim data ke view
        return view('receptionist.guest', compact('reservations'));
    }
    
    
    public function showRoomsData()
    {
        $rooms = Room::with('roomType')->get(); // Load relasi tipe kamar
        return view('receptionist.rooms', compact('rooms'));
    }

    public function editRoomStatus($id)
    {
        $room = Room::findOrFail($id);
        return view('receptionist.edit-room', compact('room'));
    }

    public function updateRoomStatus(Request $request, $id)
    {
        $request->validate([
            'room_status' => 'required|in:tersedia,terisi,perawatan',
        ]);
        
        $room = Room::findOrFail($id);
        $room->room_status = $request->input('room_status');
        $room->save();

        return redirect()->route('receptionist.rooms.index', $room->id)->with('success', 'Status kamar berhasil diperbarui');
    }

    public function printInvoice($id)
    {
        $reservation = Reservation::with(['roomType', 'payment', 'serviceOrders.service'])
            ->findOrFail($id);
    
        $checkInDate = Carbon::parse($reservation->check_in_date);
        $checkOutDate = Carbon::parse($reservation->check_out_date);
        $nights = $checkInDate->diffInDays($checkOutDate);
        $serviceOrderTotal = $reservation->serviceOrders->sum('total_price');
        $grandTotal = $reservation->payment->amount + $serviceOrderTotal;
    
        $data = [
            'reservation' => $reservation,
            'nights' => $nights,
            'serviceOrderTotal' => $serviceOrderTotal,
            'grandTotal' => $grandTotal,
        ];
    
        $pdf = Pdf::loadView('print.invoice', $data);
        return $pdf->download('invoice.pdf');
    }
    
}
