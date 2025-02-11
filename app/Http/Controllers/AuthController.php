<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'full_name' => 'required|string|min:10|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:12',
            'phone_number' => 'required|string|min:11|max:15',
            'nationality' => 'nullable|string|min:3|max:100',
            'identification_type' => 'nullable|string',
            'identification_number' => 'required|string|min:11|max:20',
        ], [
            'title.required' => 'Title harus diisi.',
            'full_name.required' => 'Nama harus diisi.',
            'full_name.min' => 'Nama minimal terdiri dari 10 karakter',
            'full_name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 12 karakter.',
            'phone_number.required' => 'Nomor telepon harus diisi.',
            'phone_number.min' => 'no.telepon minimal terdiri dari 11 karakter',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari 15 karakter.',
            'nationality.min' => 'Nationality minimal terdiri dari 3 karakter',
            'nationality.max' => 'Nationality tidak boleh lebih dari 100 karakter.',
            // 'identification_type.required' => 'Tipe identitas harus diisi.',
            'identification_number.required' => 'Nomor identitas harus diisi.',
            'identification_number.min' => 'Nomor identitas minimal terdiri dari 11 karakter.',
            'identification_number.max' => 'Nomor identitas tidak boleh lebih dari 20 karakter.',
        ]);
    
        $user = User::create([
            'title' => $request->title,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'nationality' => $request->nationality,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'role' => 'user',
        ]);
    
        $user->assignRole('user');
    
        Auth::login($user);
    
        return redirect('/login')->with('success', 'Registration successful!');
    }
    

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:12',
        ], [
            'email.required' => 'Silahkan isi email Anda terlebih dahulu.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Silahkan isi Password Anda terlebih dahulu.',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 12 karakter.',
        ]);
    
        // Pastikan logout jika ada sesi aktif
        Auth::logout();

        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email yang dimasukkan salah.'])->withInput();
        }
    
        // Coba login dengan Auth::attempt()
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['password' => 'Password yang dimasukkan salah.'])->withInput();
        }
    
        $user = Auth::user();

        if ($user) {
    
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
    
        // return back()->withErrors([
        //     'email' => 'Email tidak sesuai',
        //     'password' => 'Password tidak sesuai'
        // ])->withInput();
        
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

