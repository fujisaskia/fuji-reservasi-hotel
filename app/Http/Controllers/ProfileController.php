<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'nationality' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'identification_type' => 'nullable|string|max:50',
            'identification_number' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $user->update($request->all());

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    // Display the edit profile form with current admin-receptionist data
    public function editProfile($id)
    {
        $user = User::findOrFail($id);

        // Menentukan layout berdasarkan role pengguna
        $layout = 'layouts.receptionist'; // Default layout

        if ($user->role === 'admin') {
            $layout = 'layouts.admin'; // Layout untuk admin
        } elseif ($user->role === 'receptionist') {
            $layout = 'layouts.receptionist'; // Layout untuk user biasa
        }

        return view('profile', compact('user', 'layout'));
    }

    // Update admin-receptionist profile
    public function updateProfile(Request $request, $id)
    {
        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Validasi inputan, pastikan email unik kecuali untuk user yang sedang login
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
        ]);
    
        // Update data user
        $user->full_name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
    
        // Simpan perubahan
        $user->save();
    
        // Redirect dengan pesan sukses
        return redirect()->route('edit.profile', $id)->with('success', 'Profile updated successfully.');
    }
}

