<?php

// HotelSettingController.php
namespace App\Http\Controllers;

use App\Models\HotelSetting;
use App\Models\HotelPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function index()
    {
        $hotelSetting = HotelSetting::with('photos')->first(); // Ambil pengaturan hotel beserta fotonya
        return view('admin.hotel-setting', compact('hotelSetting'));
    }


    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', // Validasi per foto
            'photos' => 'array|max:3', // Validasi maksimal 3 foto
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
        ]);
    
        $hotelSetting = HotelSetting::first() ?? new HotelSetting();
    
        // Simpan logo jika diunggah
        if ($request->hasFile('logo')) {
            if ($hotelSetting->logo_path) {
                Storage::disk('public')->delete($hotelSetting->logo_path); // Hapus logo lama jika ada
            }
            $logoPath = $request->file('logo')->store('hotel_logos', 'public');
            $hotelSetting->logo_path = $logoPath;
        }
    
        $hotelSetting->name = $request->input('name');
        $hotelSetting->description = $request->input('description');
        $hotelSetting->address = $request->input('address');
        $hotelSetting->email = $request->input('email');
        $hotelSetting->phone = $request->input('phone');
        $hotelSetting->instagram = $request->input('instagram');
        $hotelSetting->facebook = $request->input('facebook');
        $hotelSetting->tiktok = $request->input('tiktok');
        $hotelSetting->whatsapp = $request->input('whatsapp');
        
        $hotelSetting->save();
    
        // Simpan foto-foto
        if ($request->hasFile('photos')) {
            // Hapus semua foto lama sebelum menyimpan yang baru (opsional)
            $hotelSetting->photos()->delete();
    
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('hotel_photos', 'public');
                HotelPhoto::create([
                    'hotel_setting_id' => $hotelSetting->id,
                    'photo_path' => $path,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Pengaturan hotel diperbarui!');
    }
    
}

