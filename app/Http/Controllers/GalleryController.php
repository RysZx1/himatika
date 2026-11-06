<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => Gallery::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Galleries/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'activity_name' => 'nullable|string|max:255',
            'activity_date' => 'nullable|date',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // Batasi cuma gambar standar web
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        Gallery::create([
            'title' => $validated['title'],
            'activity_name' => $validated['activity_name'],
            'activity_date' => $validated['activity_date'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.galleries.index')->with('message', 'Foto berhasil diunggah!');
    }

    public function destroy(Gallery $gallery)
    {
        // Hapus file fisik dari storage
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        // Hapus data dari database
        $gallery->delete();

        // Kembali ke halaman index galeri
        return redirect()->route('admin.galleries.index')->with('message', 'Foto dokumentasi berhasil dihapus!');
    }
}