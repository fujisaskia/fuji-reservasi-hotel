<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        // Ambil semua data room beserta tipe kamar dengan eager loading
        $rooms = Room::with('roomType')->get();
    
        return view('admin.room-manage', compact('rooms'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('admin.add-room', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|integer',
            'room_type' => 'required|exists:room_types,id',
            'room_status' => 'required|in:tersedia,perawatan,terisi'
        ]);

        Room::create([
            'room_number' => $request->room_number,
            'room_type_id' => $request->room_type,
            'room_status' => $request->room_status,
        ]);

        // Mengalihkan ke daftar tipe kamar dengan pesan sukses
        return redirect()->route('rooms.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Kamar berhasil ditambahkan',
        ]);
    }

    public function edit($id)
    {
        // Ambil data kamar berdasarkan ID
        $room = Room::findOrFail($id);
        // Ambil semua tipe kamar untuk dropdown
        $roomTypes = RoomType::all();
        
        // Tampilkan form edit dengan data kamar dan tipe kamar
        return view('admin.edit-room', compact('room', 'roomTypes'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'room_number' => 'required|integer',
            'room_type_id' => 'required|exists:room_types,id',
            'room_status' => 'required|string',
        ]);

        // Cari data kamar berdasarkan ID dan update
        $room = Room::findOrFail($id);
        $room->room_number = $request->room_number;
        $room->room_type_id = $request->room_type_id;
        $room->room_status = $request->room_status;
        $room->save();

        // Redirect kembali ke halaman daftar kamar dengan pesan sukses
        // Mengalihkan ke daftar tipe kamar dengan pesan sukses
        return redirect()->route('rooms.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Kamar berhasil diperbaharui.',
        ]);
    }

    public function destroy($id)
    {
        // Cari data kamar berdasarkan ID dan hapus
        $room = Room::findOrFail($id);
        $room->delete();
    
        // Redirect kembali ke halaman daftar kamar dengan pesan sukses
        // Mengalihkan ke daftar tipe kamar dengan pesan sukses
        return redirect()->route('rooms.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Kamar berhasil dihapus!.',
        ]);
    }
}
