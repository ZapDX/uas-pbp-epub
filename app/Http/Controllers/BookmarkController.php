<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    // 1. Toggle Bookmark (Simpan/Hapus)
    public function toggle(Book $book)
    {
        $user = Auth::user();

        // Cek apakah sudah di-bookmark?
        if ($user->bookmarks()->where('book_id', $book->id)->exists()) {
            // Kalau sudah, hapus (Un-bookmark)
            $user->bookmarks()->detach($book->id);
            $message = 'Buku dihapus dari bookmark.';
        } else {
            // Kalau belum, simpan (Bookmark)
            $user->bookmarks()->attach($book->id);
            $message = 'Buku berhasil disimpan ke bookmark!';
        }

        return back()->with('success', $message);
    }

    // 2. Halaman Daftar Bookmark
    public function index()
    {
        $books = Auth::user()->bookmarks()->latest()->get();
        // Kita gunakan view dashboard tapi datanya khusus bookmark
        // Kita perlu sedikit trik di view dashboard nanti
        return view('dashboard', [
            'books' => $books, 
            'categories' => \App\Models\Category::all(),
            'isBookmarkPage' => true // Penanda bahwa ini halaman bookmark
        ]);
    }
}