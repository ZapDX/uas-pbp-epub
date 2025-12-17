<?php

namespace App\Models;

// BARIS INI YANG SEBELUMNYA HILANG:
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory; // Sekarang ini tidak akan error lagi

    protected $guarded = [];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}