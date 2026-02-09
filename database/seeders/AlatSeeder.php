<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Support\Str;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = Kategori::all();

        foreach ($kategoris as $kategori) {

            // Setiap kategori punya 3–6 alat
            $jumlahAlat = rand(3, 6);

            for ($i = 1; $i <= $jumlahAlat; $i++) {
                Alat::create([
                    'id_kategori' => $kategori->id,
                    'nama_alat'   => $kategori->nama_kategori . ' ' . Str::upper(Str::random(4)),
                    'stok'        => rand(1, 15),
                    'kondisi'     => collect(['Baik', 'Rusak'])->random(),
                    'gambar'      => 'alat/default.png',
                ]);
            }
        }
    }
}