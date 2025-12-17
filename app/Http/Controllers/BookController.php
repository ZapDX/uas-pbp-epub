<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // 1. Menampilkan Form Upload
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    // 2. Menyimpan Data ke Database (Validasi .epub)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            // Wajib .epub sesuai spesifikasi UAS
            'file' => 'required|file|mimes:epub|max:10240', 
            'cover' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        // Upload File ePub
        $filePath = $request->file('file')->store('books', 'public');

        // Upload Cover
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Book::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'file_path' => $filePath,
            'cover_path' => $coverPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Buku ePub berhasil diunggah!');
    }

    // 3. Menampilkan Halaman Baca (Reader)
    public function show(Book $book) // Gunakan Model Binding agar konsisten
    {
        // Generate URL file agar bisa dibaca JS
        $fileUrl = Storage::url($book->file_path);

        return view('books.show', compact('book', 'fileUrl'));
    }

    // 4. Menampilkan Form Edit
    public function edit(Book $book)
    {
        // Pastikan hanya pemilik yang bisa edit
        if (auth()->id() !== $book->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = \App\Models\Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    // 5. Update Data Buku
    public function update(Request $request, Book $book)
    {
        if (auth()->id() !== $book->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|max:2048', 
            'description' => 'nullable|string',
        ]);

        // Update data teks
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->category_id = $request->category_id;

        // Cek jika ada upload cover baru
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($book->cover_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->cover_path);
            }
            // Simpan yang baru
            $book->cover_path = $request->file('cover')->store('covers', 'public');
        }

        $book->save();

        return redirect()->route('dashboard')->with('success', 'Buku berhasil diperbarui!');
    }

    // 6. Hapus Buku
    public function destroy(Book $book)
    {
        if (auth()->id() !== $book->user_id) {
            abort(403);
        }

        // Hapus file fisik (ePub & Cover) agar hemat storage
        if ($book->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
        }
        if ($book->cover_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->cover_path);
        }

        // Hapus data di database
        $book->delete();

        return redirect()->route('dashboard')->with('success', 'Buku berhasil dihapus!');
    }

} 