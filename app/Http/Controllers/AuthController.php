<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{
    // Fungsi untuk register
    public function register(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:15',
            'nationality' => 'required|string|max:100',
            'identification_type' => 'required|string',
            'identification_number' => 'required|string|max:20',
        ]);

        $user = User::create([
            'title' => $request->title,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'nationality' => $request->nationality,
            'identification_type' => $request->identification_type, // Tambahkan tipe identitas
            'identification_number' => $request->identification_number, // Tambahkan nomor identitas
            'role' => 'user', // Set default role
        ]);        

        // Assign role "user" by default
        $user->assignRole('user');

        // Auto-login setelah register
        Auth::login($user);

        return redirect('/login')->with('success', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        // Pastikan logout jika ada sesi aktif
        Auth::logout();
    
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
    
            // Cek role user dan arahkan ke halaman dashboard sesuai role
            if ($user->role === 'admin') {
                //ditambahin sama mas Roy, buat middleware nya sampai baris 40 
                Auth::guard('admin')->login($user);          
                $request->session()->regenerate();

                return redirect('/dashboard/admin')->with('success', 'Login successful!');
            } elseif ($user->role === 'receptionist') {
                Auth::guard('receptionist')->login($user);          
                $request->session()->regenerate();
                
                return redirect('/dashboard/receptionist')->with('success', 'Login successful!');
            } elseif ($user->role === 'user') {
                Auth::login($user); 
                $request->session()->regenerate();

                return redirect('/offers')->with('success', 'Login successful!');
            }
    
            // Logout jika role tidak sesuai
            Auth::logout();
            return back()->withErrors(['email' => 'You do not have access to this area.']);
        }
    
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }
    
    

    // Fungsi untuk logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus sesi jika diperlukan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login atau halaman utama
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    // Fungsi untuk logout
    public function logoutReceptionist(Request $request)
    {
        Auth::guard('receptionist')->logout();
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();

        // Redirect ke halaman login atau halaman utama
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    // Fungsi untuk logout
    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();

        // Redirect ke halaman login atau halaman utama
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }

}

