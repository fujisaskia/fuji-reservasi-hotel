<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Reservation;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmationMail;

class AdminController extends Controller
{
    // Fungsi untuk menghitung jumlah reservasi berdasarkan status pembayaran dan status reservasi di Admin
    public function index(Request $request)
    {
        // Hitung total reservasi dengan status pembayaran success

        // Hitung total reservasi dengan status pembayaran success berdasarkan bulan ini
        $totalPaymentSuccess = Reservation::whereHas('payment', function ($query) {
            $query->where('payment_status', 'success');
        })
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();
        

        // Hitung total reservasi dengan status reservasi confirmed
        $totalConfirmed = Reservation::where('reservation_status', 'Confirmed')->count();

        // Hitung jumlah reservasi yang memiliki status 'Cancelled' dan payment_status 'success'
        $totalPending = Reservation::where('reservation_status', 'Pending')
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'success');
            })
            ->count();


        // Hitung total reservasi dengan status reservasi cancelled
        $totalCancelled = Reservation::where('reservation_status', 'Cancelled')->count();

        return view('admin.dashboard', compact('totalPaymentSuccess', 'totalConfirmed', 'totalPending', 'totalCancelled'));
    }

    //fitur pencarian reservasi
    public function adminReservations(Request $request)
    {
        $query = Reservation::with(['invoice', 'payment'])
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'success');
            });
    
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
            ->paginate(10);

        return view('admin.reservasi', compact('reservations'));
    }

    //konfirmasi reservasi
    public function adminConfirmReservation($id)
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

    //menerapkan filter untuk mencetak data reservasi
    public function filter(Request $request)
    {
        $query = Reservation::with(['user', 'roomType']);
    
        if ($request->status) {
            $query->where('reservation_status', $request->status);
        }
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }
    
        $reservations = $query->get();
    
        return response()->json($reservations);
    }
    
    //mencetak data reservasi sesuai filter
    public function cetakLaporan(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:Pending,Confirmed,Checked-In,Checked-Out,Cancelled',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2020|max:' . date('Y'),
        ]);
    
        $query = Reservation::with(['invoice', 'payment']);
    
        if ($request->status) {
            $query->where('reservation_status', $request->status);
        }
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }
    
        $reservations = $query->get();
    
        // Cetak PDF
        $pdf = Pdf::loadView('print.reservasi-laporan', [
            'reservations' => $reservations,
            'status' => $request->status,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);
        return $pdf->download('laporan-reservasi.pdf');
    }

    //menampilkan user yang sudah check-in ke fitur tamu receptionist
    public function showGuest(Request $request) {
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
        return view('admin.guest', compact('reservations'));
    }



    /**
     * Menampilkan daftar layanan berdasarkan reservation.
     */
    public function showServiceRoomByReservation($reservationId)
    {
        // Ambil data reservation berdasarkan ID
        $reservation = Reservation::find($reservationId);
        // Ambil invoice terkait dengan reservasi
        $invoice = $reservation->invoice; // Menggunakan relasi untuk mengambil data invoice

        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation tidak ditemukan.');
        }

        // Ambil data service orders yang terkait dengan reservation
        $serviceOrder = ServiceOrder::where('reservation_id', $reservation->id)->get();

        // Hitung total harga
        $totalHarga = $serviceOrder->sum('total_price');

        // Kirim data ke view
        return view('admin.detail-layanan', [
            'reservation' => $reservation,
            'invoice' => $invoice,
            'serviceOrder' => $serviceOrder,
            'totalHarga' => $totalHarga,
        ]);
    }

    /**
     * Menghapus pesan layanan
     */
    public function deleteServiceOrderGuest(Request $request)
    {
        $orderId = $request->input('order_id');
        ServiceOrder::where('id', $orderId)->delete();
        return response()->json(['message' => 'Layanan berhasil dihapus!']);
    }

    /**
     * mencetak invoice layanan sebagai bukti
     */
    public function printSelectedServicesGuest(Request $request)
    {
        // Validasi bahwa service_ids ada dalam request dan merupakan array
        $request->validate([
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:service_orders,id', // Pastikan ID layanan valid
        ]);

        // Ambil ID layanan yang dipilih dari permintaan
        $serviceIds = $request->input('service_ids');

        // Ambil data layanan yang sesuai dengan ID yang dipilih, dengan relasi terkait
        $services = ServiceOrder::whereIn('id', $serviceIds)
            ->with(['service', 'reservation.user', 'reservation.room']) // Pastikan relasi terkait diambil
            ->get();

        if ($services->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada layanan yang dipilih.');
        }

        // Ambil data reservasi yang terkait dengan layanan
        $reservations = $services->map(function ($service) {
            return $service->reservation; // Mengambil data reservasi terkait dari setiap layanan
        })->unique();

        // Hitung total harga dari layanan yang dipilih
        $totalHarga = $services->sum('total_price');

        // Generate PDF menggunakan view yang sudah disesuaikan
        $pdf = Pdf::loadView('print.layanan-kamar', [
            'services' => $services,
            'reservations' => $reservations,
            'totalHarga' => $totalHarga,
        ])->setPaper('a4', 'portrait');

        // Unduh PDF
        return $pdf->download('layanan-kamar.pdf');
    }



    // Menampilkan daftar pengguna
    public function showUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Menampilkan form tambah pengguna
    public function userCreate()
    {
        return view('admin.user-create');
    }

    // Menyimpan pengguna baru
    public function userStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:10',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,receptionist,user',
            'phone_number' => 'required|string|max:20',
            'nationality' => 'required|string|max:50',
            'identification_type' => 'required|string|max:50',
            'identification_number' => 'required|string|max:50|unique:users',
        ]);

        User::create([
            'title' => $request->title,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'nationality' => $request->nationality,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
        ]);

        return redirect()->route('users.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Pengguna berhasil ditambah',
        ]);
    }

    // Menampilkan form edit pengguna
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }

    // Update data pengguna
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:10',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,receptionist,user',
            'phone_number' => 'required|string|max:20',
            'nationality' => 'required|string|max:50',
            'identification_type' => 'required|string|max:50',
            'identification_number' => 'required|string|max:50|unique:users,identification_number,' . $id,
        ]);

        $user->update([
            'title' => $request->title,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'nationality' => $request->nationality,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
        ]);

        return redirect()->route('users.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Pengguna berhasil diperbarui',
        ]);
    }

    // Menghapus pengguna
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Pengguna berhasil dihapus!',
        ]);
    }

}
