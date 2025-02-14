<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Invoice;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ReservationRoom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCheckoutSuccessMail;
use App\Mail\ReservationConfirmationMail;


class ReceptionistController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['invoice', 'payment'])
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'success');
            })
            // Kecualikan reservasi yang statusnya 'Checked-Out'
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
    
        // Urutkan berdasarkan status "pending" dan "confirmed" di atas
        $reservations = $query->orderByRaw("
                CASE 
                    WHEN reservation_status = 'pending' THEN 1 
                    WHEN reservation_status = 'confirmed' THEN 2 
                    WHEN reservation_status = 'checked-in' THEN 2 
                    ELSE 3 
                END
            ")
            ->paginate(10);
    
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

            return redirect()->back()->with('sweetalert', [
                'type' => 'success',
                'message' => 'Reservasi oleh "' . $userName . '" berhasil dikonfirmasi!'
            ]);
        } else {
            return redirect()->back()->with('sweetalert', [
                'type' => 'error',
                'message' => 'Reservasi tidak dapat dikonfirmasi, mohon periksa status reservasi terlebih dahulu.'
            ]);
        }
    }

    //cancelled reservation by receptionist
    public function cancelReservationByReceptionist(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->reservation_status === 'Confirmed') {
            $reservation->reservation_status = 'Cancelled';
            $reservation->save();

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Reservasi tidak dapat dibatalkan.',
        ], 400);
    }

    public function showAvailableRooms()
    {
        $availableRoomTypes = RoomType::whereHas('rooms', function ($query) {
            $query->where('room_status', 'tersedia');
        })->get();
    
        return view('receptionist.check-in', compact('availableRoomTypes'));
    }

    //menampilkan form check-in
    public function showCheckInForm($id)
    {
        // Ambil tipe kamar berdasarkan ID yang dipilih
        $roomType = RoomType::findOrFail($id);
    
        // Ambil semua kamar yang termasuk dalam tipe kamar tersebut dan tersedia
        $rooms = Room::where('room_type_id', $roomType->id)
                     ->where('room_status', 'tersedia')
                     ->get();
    
        // Ambil reservasi yang statusnya "Confirmed" dan memiliki room_type_id yang dipilih
        $reservations = Reservation::where('reservation_status', 'Confirmed')
                        ->whereHas('roomType', function ($query) use ($roomType) {
                            $query->where('room_type_id', $roomType->id);
                        })
                        ->get();
    
        // Ambil invoice dari reservasi yang telah difilter
        $invoices = Invoice::whereIn('reservation_id', $reservations->pluck('id'))->get();
    
        // Menambahkan perhitungan jumlah malam
        $reservationsWithNights = $reservations->map(function($reservation) {
            // Menghitung selisih malam antara check-in dan check-out
            $checkInDate = \Carbon\Carbon::parse($reservation->check_in_date);
            $checkOutDate = \Carbon\Carbon::parse($reservation->check_out_date);
            $nights = $checkInDate->diffInDays($checkOutDate); // Menghitung jumlah malam
            $reservation->nights = $nights; // Menambahkan jumlah malam ke objek reservasi
            return $reservation;
        });
    
        return view('receptionist.in-room', compact('roomType', 'rooms', 'reservationsWithNights', 'invoices', 'reservations'));
    }
    
    

    //proses check-in
    public function processCheckIn(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'room_id' => 'required|array',
            'room_id.*' => 'exists:rooms,id',
            'deposit' => 'nullable|numeric|min:0',
        ]);

        // dd($validatedData);
    
        // Ambil reservasi berdasarkan ID
        $reservation = Reservation::findOrFail($validatedData['reservation_id']);
    
        // Periksa apakah reservasi valid dan statusnya "Confirmed"
        if ($reservation->reservation_status !== 'Confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak valid atau sudah di-check-in.',
            ], 400);
        }
    
        try {
            DB::transaction(function () use ($validatedData, $reservation) {
                foreach ($validatedData['room_id'] as $roomId) {
                    $room = Room::findOrFail($roomId);
    
                    // Periksa apakah kamar tersedia
                    // if ($room->room_status !== 'tersedia') {
                    //     throw new \Exception("Kamar {$room->room_number} tidak tersedia untuk check-in.");
                    // }
    
                    // Periksa apakah kamar yang dipilih sesuai dengan tipe kamar yang dipesan
                    if ($room->room_type !== $reservation->room_type) {
                        throw new \Exception("Tipe kamar {$room->room_number} tidak sesuai dengan reservasi.");
                    }
    
                    // Tambahkan kamar ke tabel pivot
                    $reservation->room()->attach($room->id);
    
                    // Update status kamar menjadi 'terisi'
                    $room->update(['room_status' => 'terisi']);
                }
    
                // Hitung jumlah kamar yang sudah dipilih di pivot
                $checkedInRooms = $reservation->room()->count();
    
                // Jika jumlah kamar yang dipilih di pivot sudah sama dengan total_rooms
                if ($checkedInRooms >= $reservation->total_room) {
                    // Ubah status reservasi menjadi 'Checked-In'
                    $reservation->update(['reservation_status' => 'Checked-In']);
                }
    
                // Update kolom deposit di tabel invoices
                Invoice::where('reservation_id', $reservation->id)->update([
                    'deposit' => $validatedData['deposit'],
                ]);
            });
    
            // Return JSON response sukses
            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil dan deposit telah ditambahkan!',
                'redirect_url' => route('receptionist.dashboard'),
            ]);
        } catch (\Exception $e) {
            // Tangani error jika ada kegagalan dalam proses transaksi
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses check-in. Silakan coba lagi.'. $e->getMessage(),
            ], 500);
        }
    }

    //menampilkan data kamar terisi dan reservasi check-in
    public function getCheckedInRooms()
    {
        $occupiedRooms = Room::where('room_status', 'terisi') // Hanya kamar yang terisi
            ->whereHas('reservation', function ($query) {
                $query->where('reservation_status', 'Checked-In'); // Hanya reservasi Checked-In
            })
            ->with(['reservation' => function ($query) {
                $query->where('reservation_status', 'Checked-In')->distinct(); // Hindari duplikasi reservasi
            }, 'reservation.user'])
            ->distinct() // Hindari duplikasi kamar
            ->get();
    
        return view('receptionist.check-out', compact('occupiedRooms'));
    }
    
    public function showInvoiceByReservation($reservationId)
    {
        // Ambil data reservasi berdasarkan ID reservasi
        $reservation = Reservation::with(['room', 'serviceOrders', 'payment', 'invoice'])
            ->where('id', $reservationId) // Menggunakan ID reservasi
            ->where('reservation_status', '!=', 'Checked-Out') // Pastikan reservasi belum check-out
            ->firstOrFail();
    
        // Ambil detail kamar berdasarkan ID kamar yang terkait dengan reservasi
        $room = $reservation->room()->with('roomType')->firstOrFail();
    
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
    
    
    //proses check-out
    public function processCheckout(Request $request)
    {
        $selectedRooms = $request->input('selected_rooms', []);
        $reservationIds = $request->input('reservation_ids', []);

        if (empty($selectedRooms)) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu kamar untuk checkout.');
        }

        DB::beginTransaction();

        try {
            foreach ($selectedRooms as $roomId) {
                $reservationId = $reservationIds[$roomId] ?? null;

                if (!$reservationId) {
                    continue;
                }

                $room = Room::find($roomId);
                $reservation = Reservation::find($reservationId);

                if ($room && $reservation) {
                    $checkedOutRooms = ReservationRoom::where('reservation_id', $reservation->id)
                        ->whereHas('room', function ($query) {
                            $query->where('room_status', 'perawatan');
                        })
                        ->count();

                    $totalRooms = $reservation->total_room;

                    $serviceOrderTotal = $reservation->serviceOrders()->sum('total_price');

                    $invoice = $reservation->invoice;
                    if ($invoice) {
                        $invoice->update([
                            'total_amount' => $invoice->total_amount + $serviceOrderTotal,
                        ]);
                    }

                    if ($checkedOutRooms + 1 < $totalRooms) {
                        $room->update(['room_status' => 'perawatan']);
                    } else {
                        $room->update(['room_status' => 'perawatan']);
                        $reservation->update(['reservation_status' => 'Checked-Out']);

                        // Kirim email ke tamu
                        // \Log::info("Mengirim email ke: " . $reservation->user->email);
                        // Mail::to($reservation->user->email)->send(new InvoiceCheckoutSuccessMail($reservation));
                        // \Log::info("Email berhasil dikirim.");                        
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Tamu Berhasil Check-Out');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat Check-Out: ' . $e->getMessage());
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


    public function showRoomsData(Request $request)
    {
        // Ambil parameter pencarian dan filter
        $search = $request->input('search');
        $status = $request->input('status');

        // Query data kamar dengan filter dan pencarian
        $rooms = Room::with('roomType')
            ->when($search, function ($query, $search) {
                return $query->where('room_number', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query, $status) {
                return $query->where('room_status', $status);
            })
            ->paginate(10);

        return view('receptionist.rooms', compact('rooms', 'search', 'status'));
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

        return redirect()->route('receptionist.rooms.index', $room->id)->with('sweetalert', [
            'type' => 'success',
            'message' => 'Status kamar berhasil diperbaharui.',
        ]);
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
        // Gunakan nomor invoice dari data reservasi
        $invoiceNumber = $reservation->invoice->invoice_number ?? 'default-invoice'; // Gunakan fallback jika nomor invoice tidak ada

        $data = [
            'reservation' => $reservation,
            'nights' => $nights,
            'serviceOrderTotal' => $serviceOrderTotal,
            'grandTotal' => $grandTotal,
        ];

        $pdf = Pdf::loadView('print.invoice', $data);
        return $pdf->download("summary-{$invoiceNumber}.pdf");
    }

    public function sendInvoiceEmail($reservationId)
    {
        // Ambil data reservasi berdasarkan ID reservasi
        $reservation = Reservation::with(['room', 'serviceOrders', 'payment', 'invoice'])
            ->where('id', $reservationId) // Menggunakan ID reservasi
            ->where('reservation_status', '!=', 'Checked-Out') // Pastikan reservasi belum check-out
            ->firstOrFail();
    
        // Ambil detail kamar berdasarkan ID kamar yang terkait dengan reservasi
        $room = $reservation->room()->with('roomType')->firstOrFail();
    
        // Hitung rincian seperti di showInvoiceByReservation
        $checkInDate = Carbon::parse($reservation->check_in_date);
        $checkOutDate = Carbon::parse($reservation->check_out_date);
        $nights = $checkInDate->diffInDays($checkOutDate);
        $totalRoom = $reservation->total_room;
        $roomPricePerNight = $reservation->payment->amount / ($nights * $totalRoom);
        $roomPaymentTotal = $reservation->payment->amount;
        $serviceOrderTotal = $reservation->serviceOrders->sum('total_price');
        $grandTotal = $roomPaymentTotal + $serviceOrderTotal;
        $deposit = $reservation->invoice->deposit;
    
        if ($serviceOrderTotal > $deposit) {
            $additionalPaymentRequired = $serviceOrderTotal - $deposit;
            $remainingDeposit = 0;
        } else {
            $remainingDeposit = $deposit - $serviceOrderTotal;
            $additionalPaymentRequired = 0;
        }
    
        // Kirim email
        Mail::to($reservation->user->email) // Ganti dengan email tamu yang sesuai
            ->send(new InvoiceCheckoutSuccessMail($room, $reservation, $roomPaymentTotal, $serviceOrderTotal, $grandTotal, $nights, $deposit, $remainingDeposit, $additionalPaymentRequired, $roomPricePerNight));
    
            return redirect()->back()->with('success', 'Invoice Email berhasil terkirim!');
    }
    
    
}
