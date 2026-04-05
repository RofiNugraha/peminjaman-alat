<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Elektronik',
            'Kebersihan',
            'Hiburan',
            'Pakaian',
            'Perkakas',
            'Dekorasi',
            'Kategori 1',
            'Kategori 2',
            'Kategori 3',
            'Kategori 4',
            'Kategori 5',
            'Kategori 6',
        ];

        foreach ($kategoris as $nama) {
            Kategori::create([
                'nama_kategori' => $nama,
                'keterangan' => 'Kategori untuk perangkat ' . strtolower($nama),
            ]);
        }
    }
}