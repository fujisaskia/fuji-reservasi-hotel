<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Payment;
use App\Models\RoomType;
use App\Models\Reservation;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use App\Models\PaymentService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller

{
    public function getMonthlyRevenue()
    {
        $roomRevenue = Payment::where('payment_status', 'success')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    
        $serviceRevenue = ServiceOrder::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();;
    
        // Format untuk 12 bulan (Jan - Dec)
        $formattedRoomRevenue = array_fill(1, 12, 0);
        foreach ($roomRevenue as $month => $total) {
            $formattedRoomRevenue[$month] = $total;
        }
    
        $formattedServiceRevenue = array_fill(1, 12, 0);
        foreach ($serviceRevenue as $month => $total) {
            $formattedServiceRevenue[$month] = $total;
        }
    
        return response()->json([
            'roomRevenue' => array_values($formattedRoomRevenue),
            'serviceRevenue' => array_values($formattedServiceRevenue),
        ]);
    }

    public function receptionistDashboard(Request $request)
    {
        $roomTypes = RoomType::all();
        
        // Ambil tipe kamar yang dipilih (jika ada)
        $roomTypeId = $request->input('room_type_id');
        
        // Hitung jumlah status kamar
        $countOccupied = $this->getRoomCount('terisi', $roomTypeId);
        $countAvailable = $this->getRoomCount('tersedia', $roomTypeId);
        $countCleaning = $this->getRoomCount('perawatan', $roomTypeId);
        
        // Ambil data check-in hari ini
        $today = Carbon::today();
        $checkInReservations = Reservation::where('reservation_status', 'Checked-In') //tamu yang melakukan check in
            ->whereDate('check_in_date', $today)
            ->get();
        
        // Ambil data check-out hari ini
        $checkOutReservations = Reservation::where('reservation_status', 'Checked-In') //tamu yang sudah check-out
            ->whereDate('check_out_date', $today)
            ->get();
        
        return view('receptionist.dashboard', compact('countOccupied', 'countAvailable', 'countCleaning', 'roomTypes', 'checkInReservations', 'checkOutReservations'));
    }
    
    // Fungsi untuk menghitung jumlah kamar berdasarkan status dan tipe
    private function getRoomCount($status, $roomTypeId = null)
    {
        $query = Room::where('room_status', $status);
    
        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }
    
        return $query->count();
    }
    
    
}
