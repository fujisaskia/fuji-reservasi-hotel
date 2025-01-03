<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        // Ambil semua data layanan beserta kategori
        $services = Service::with('serviceCategory')->get();

        // Tampilkan view dengan data layanan
        return view('admin.service', compact('services'));
    }

    // Method untuk menampilkan form create
    public function create()
    {
        // Ambil semua kategori layanan
        $categories = ServiceCategory::all();

        // Tampilkan form create dengan data kategori
        return view('admin.create-service', compact('categories'));
    }

    // Method untuk menyimpan layanan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'service_category_id' => 'required|exists:service_categories,id',
            'price' => 'required|numeric|min:0',
        ]);

        // Simpan data layanan ke database
        Service::create([
            'name' => $request->name,
            'service_category_id' => $request->service_category_id,
            'price' => $request->price,
        ]);

        // Redirect setelah berhasil menyimpan data
        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan!');
    }

    // / Menampilkan form edit untuk layanan
    public function edit($id)
    {
        // Ambil data layanan berdasarkan ID
        $service = Service::findOrFail($id);
        
        // Ambil semua kategori layanan
        $categories = ServiceCategory::all();

        // Tampilkan form edit dengan data layanan dan kategori
        return view('admin.edit-service', compact('service', 'categories'));
    }

    // Memperbarui data layanan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'service_category_id' => 'required|exists:service_categories,id',
            'price' => 'required|numeric',
        ]);

        // Ambil data layanan berdasarkan ID dan update
        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'service_category_id' => $request->service_category_id,
            'price' => $request->price,
        ]);

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy($id)
    {
        // Cari layanan berdasarkan ID
        $service = Service::findOrFail($id);

        // Hapus layanan tersebut
        $service->delete();

        // Redirect ke halaman daftar layanan dengan pesan sukses
        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus!');
    }   

}
