<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    /**
     * Menampilkan Data ulasan.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mengambil query pencarian dari input
        $search = $request->input('search');
    
        // Mengambil ulasan yang berelasi dengan user, dan melakukan pencarian pada kolom 'ulasan' dan 'name'
        $ulasans = Ulasan::with(['user'])
            ->when($search, function ($query, $search) {
                return $query->where('comment', 'like', '%' . $search . '%')
                             ->orWhereHas('user', function ($query) use ($search) {
                                 $query->where('full_name', 'like', '%' . $search . '%');
                             });
            })
            ->paginate(25);
    
        // Kembalikan data ke view
        return view('admin.ulasan', compact('ulasans'));
    }
    

    /**
     * Menampilkan halaman form ulasan.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('user.ulasan-form');
    }

    /**
     * Menyimpan ulasan ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Ulasan::create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Terimakasih telah memberikan ulasan, semoga hari anda menyenangkan!.');
    }

    public function toggleVisibility($id)
    {
        $ulasan = Ulasan::findOrFail($id);
        
        // Toggle visibilitas ulasan
        $ulasan->is_visible = !$ulasan->is_visible;
        $ulasan->save();
    
        // Set session flash message untuk success
        $message = $ulasan->is_visible ? 'Ulasan berhasil ditampilkan.' : 'Ulasan berhasil disembunyikan.';
        
        // Redirect kembali dengan pesan sukses
        return redirect()->route('ulasans.index')->with('success', $message);
    }
    
}
