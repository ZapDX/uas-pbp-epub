<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN DEPAN (PUBLIC SEARCH & RECOMMENDATION) ---
Route::get('/', function (Request $request) {
    // Logika Search
    $searchResults = null;
    if ($request->filled('search')) {
        $search = $request->search;
        $searchResults = Book::with('category')
            ->where('title', 'like', "%{$search}%")
            ->orWhere('author', 'like', "%{$search}%")
            ->latest()
            ->get();
    }

    // Logika Rekomendasi
    $recommendations = Book::with('category')
        ->inRandomOrder() 
        ->limit(15) 
        ->get();

    return view('welcome', compact('searchResults', 'recommendations'));
})->name('welcome');


// --- 2. AREA PROTECTED (UPLOAD & EDIT HARUS DI ATAS 'SHOW') ---
// PENTING: Grup ini harus diletakkan SEBELUM rute '/books/{book}'
// Agar kata "upload" tidak dianggap sebagai ID buku.
Route::middleware(['auth'])->group(function () {

    // Upload (Create & Store)
    Route::get('/books/upload', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');

    // Edit & Update
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');

    // Hapus
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Bookmark Routes
    Route::post('/books/{book}/bookmark', [App\Http\Controllers\BookmarkController::class, 'toggle'])->name('books.bookmark');
    Route::get('/my-bookmarks', [App\Http\Controllers\BookmarkController::class, 'index'])->name('bookmarks.index');
});


// --- 3. AKSES BACA BUKU (PUBLIC / SIAPAPUN BISA BACA) ---
// Rute ini ditaruh di bawah agar menjadi "pilihan terakhir" untuk URL yang diawali /books/
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');


// --- 4. DASHBOARD (USER LOGIN ONLY) ---
Route::get('/dashboard', function (Request $request) {
    $query = Book::with('category')->latest();

    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%");
        });
    }

    if ($request->has('category')) {
        $query->where('category_id', $request->category);
    }

    $books = $query->get();
    $categories = Category::all();
    
    return view('dashboard', compact('books', 'categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Route Khusus untuk membersihkan cache di Vercel
Route::get('/bersih-bersih', function() {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Cache berhasil dibersihkan!</h1> <br> Output: ' . Artisan::output();
});

require __DIR__.'/auth.php';