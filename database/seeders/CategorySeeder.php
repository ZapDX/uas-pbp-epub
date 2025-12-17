<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Pastikan baris ini ada
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar Prodi FST
        $prodis = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Biologi',
            'Matematika',
            'Fisika',
            'Kimia'
        ];

        foreach ($prodis as $prodi) {
            Category::create([
                'name' => $prodi,
                'slug' => Str::slug($prodi),
            ]);
        }
    }
}