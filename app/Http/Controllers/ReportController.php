<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function showReports(Request $request)
    {
        $query = Reservation::where('reservation_status', 'Checked-Out');

        // Filter berdasarkan bulan
        if ($request->has('month') && $request->month) {
            $query->whereMonth('check_out_date', $request->month);
        }
    
        // Pencarian berdasarkan nama atau invoice
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%');
            })->orWhereHas('invoice', function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%');
            });
        }
    
        $reservations = $query->get();

        return view('receptionist.laporan', compact('reservations'));
    }

    public function cetakLaporan(Request $request)
    {
        $query = Reservation::where('reservation_status', 'Checked-Out');
    
        // Filter berdasarkan bulan
        if ($request->has('month') && is_numeric($request->month)) {
            $query->whereMonth('check_out_date', (int)$request->month);
        }
    
        // Pencarian berdasarkan nama atau invoice
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%');
            })->orWhereHas('invoice', function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%');
            });
        }
    
        $reservations = $query->get();
        $month = $request->has('month') && is_numeric($request->month) ? (int)$request->month : null;
    
        $pdf = PDF::loadView('print.cetak-laporan', compact('reservations', 'month'));
        return $pdf->download('laporan-tamu.pdf');
    }

    public function showDetailLaporan($id)
    {
        // Ambil data reservasi berdasarkan ID
        $reservation = Reservation::with([
            'invoice', 
            'user', 
            'roomType', 
            'payment', 
            'serviceOrders.service'
        ])->findOrFail($id);

        // Hitung lama menginap (jumlah malam)
        $checkInDate = Carbon::parse($reservation->check_in_date);
        $checkOutDate = Carbon::parse($reservation->check_out_date);
        $nights = $checkInDate->diffInDays($checkOutDate);

        // Menghitung harga per malam untuk kamar
        $totalRoom = $reservation->total_room; // Asumsi ada total_room
        $roomPricePerNight = $reservation->payment->amount / ($nights * $totalRoom);

        // Total harga layanan
        $serviceOrderTotal = $reservation->serviceOrders->sum('total_price');

        // Grand total (kamar + layanan)
        $grandTotal = $reservation->payment->amount + $serviceOrderTotal;

        return view('receptionist.detail-laporan', compact('reservation', 'nights', 'serviceOrderTotal', 'grandTotal', 'roomPricePerNight'));
    }
    

}
