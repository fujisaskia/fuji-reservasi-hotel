<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    // Menampilkan daftar kategori layanan
    public function index()
    {
        // Mengambil semua kategori layanan dari database
        $categories = ServiceCategory::all();
        
        // Menampilkan view dengan data kategori layanan
        return view('admin.service-category', compact('categories'));
    }

    // Menampilkan form untuk membuat kategori layanan baru
    public function create()
    {
        return view('admin.create-service-category');
    }

    // Menyimpan kategori layanan baru ke dalam database
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Menyimpan data kategori layanan baru ke dalam database
        ServiceCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Mengarahkan ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('service_categories.index')->with('success', 'Category created successfully!');
    }

    // Menampilkan form untuk mengedit kategori layanan
    public function edit($id)
    {
        // Mengambil kategori layanan berdasarkan id
        $category = ServiceCategory::findOrFail($id);

        return view('admin.edit-service-category', compact('category'));
    }

    // Memperbarui kategori layanan yang sudah ada
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Mencari kategori layanan berdasarkan id
        $category = ServiceCategory::findOrFail($id);

        // Memperbarui data kategori layanan
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Mengarahkan kembali ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('service_categories.index')->with('success', 'Category updated successfully!');
    }

    // Menghapus kategori layanan
    public function destroy($id)
    {
        // Cari kategori berdasarkan ID
        $category = ServiceCategory::findOrFail($id);
    
        try {
            // Hapus kategori
            $category->delete();
    
            // Pesan sukses
            return redirect()->route('service_categories.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            // Pesan gagal
            return redirect()->route('service_categories.index')->with('error', 'Terjadi kesalahan saat menghapus kategori!');
        }
    }
    
    
}

