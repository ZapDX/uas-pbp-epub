<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            
            // Relasi: Buku milik User siapa? (Penyedia buku/Uploader)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi: Buku masuk kategori Prodi mana?
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('title'); // Judul Buku
            $table->string('author'); // Penulis
            $table->text('description')->nullable(); // Sinopsis
            
            $table->string('file_path'); // Lokasi file .epub di server (PENTING)
            $table->string('cover_path')->nullable(); // Cover buku (Opsional)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
