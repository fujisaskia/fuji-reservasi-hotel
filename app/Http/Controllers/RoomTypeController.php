<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\RoomType;
use App\Models\HotelSetting;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    // Menampilkan daftar tipe kamar
    public function index()
    {
        $roomTypes = RoomType::all(); // Mengambil semua data tipe kamar
        return view('admin.room-type', compact('roomTypes')); // Mengembalikan tampilan daftar tipe kamar
    }

    public function roomsLandingPage()
    {
        $roomTypes = RoomType::all();
        return view('rooms', ['roomTypes' => $roomTypes]);
    }

    public function roomsuites()
    {
        // Ambil pengaturan hotel beserta fotonya
        $hotelSetting = HotelSetting::with('photos')->first(); 
        
        // Hanya ambil ulasan yang is_visible = true
        $ulasans = Ulasan::with(['user'])->where('is_visible', true)->get(); // Tampilkan ulasan tamu yang visible
    
        // Ambil semua data room_types
        $roomTypes = RoomType::all(); 
    
        // Kembalikan ke view dengan data yang diperlukan
        return view('welcome', compact('roomTypes', 'ulasans', 'hotelSetting'));
    }
    
    

    // // Menampilkan form untuk menambah tipe kamar
    public function create()
    {
        return view('admin.add-type'); // Mengembalikan tampilan form tambah tipe kamar
    }

    // Menyimpan tipe kamar baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tipe_kamar' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'harga' => 'required',
            'deskripsi_kamar' => 'required|string',
            'fasilitas' => 'required|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);
    
        // Menyimpan foto ke dalam array dan memastikan disimpan di public storage
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoPaths[] = $foto->store('room_photos', 'public'); // Menyimpan di public storage
            }
        }
    
        // Membuat entri baru di tabel room_types
        RoomType::create([
            'tipe_kamar' => $request->tipe_kamar,
            'kapasitas' => $request->kapasitas,
            'harga' => $request->harga,
            'deskripsi_kamar' => $request->deskripsi_kamar,
            'fasilitas' => $request->fasilitas,
            'foto' => json_encode($fotoPaths), // Menyimpan array foto sebagai JSON
        ]);
    
        // Mengalihkan ke daftar tipe kamar dengan pesan sukses
        return redirect()->route('room-types.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Tipe kamar berhasil ditambahkan.',
        ]);
    }
    
    

    // // Menampilkan form untuk mengedit tipe kamar
    public function edit(RoomType $roomType)
    {
        return view('admin.edit-type', compact('roomType')); // Mengembalikan tampilan form edit
    }

    // // Memperbarui tipe kamar yang sudah ada
    public function update(Request $request, RoomType $roomType)
    {
        // Validasi input
        $request->validate([
            'tipe_kamar' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'harga' => 'required|numeric',
            'deskripsi_kamar' => 'required|string',
            'fasilitas' => 'required|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);
    
        // Mengambil foto lama
        $fotoPaths = json_decode($roomType->foto, true);
    
        // Menyimpan foto baru jika ada
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                // Menyimpan setiap foto baru ke folder 'room_photos' dan menyimpan path
                $fotoPaths[] = $foto->store('room_photos');
            }
        }
    
        // Mengupdate data tipe kamar
        $roomType->update([
            'tipe_kamar' => $request->tipe_kamar,
            'kapasitas' => $request->kapasitas,
            'harga' => $request->harga,
            'deskripsi_kamar' => $request->deskripsi_kamar,
            'fasilitas' => $request->fasilitas,
            'foto' => json_encode($fotoPaths), // Konversi array foto menjadi JSON string
        ]);
    
        // Redirect ke daftar tipe kamar
        return redirect()->route('room-types.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Tipe kamar berhasil diperbaharui.',
        ]);
    }
    

    // Menghapus tipe kamar
    public function destroy(RoomType $roomType)
    {
        // Cek apakah ada room dengan tipe kamar ini
        if ($roomType->rooms()->exists()) {
            // Simpan pesan error di session
            return redirect()->route('room-types.index')->with('sweetalert', [
                'type' => 'error',
                'message' => 'Anda memiliki kamar dengan tipe ini.',
            ]);
        }

        // Jika tidak ada, hapus tipe kamar
        $roomType->delete();
        return redirect()->route('room-types.index')->with('sweetalert', [
            'type' => 'success',
            'message' => 'Tipe kamar berhasil dihapus.',
        ]);
    }

}


