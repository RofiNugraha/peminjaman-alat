<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alat;
use App\Models\Kategori;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = Kategori::all();

        foreach ($kategoris as $kategori) {

            $jumlahAlat = rand(3, 6);

            for ($i = 1; $i <= $jumlahAlat; $i++) {

                $kondisi = collect(['Baik', 'Rusak'])->random();

                $stok = $kondisi === 'Baik'
                    ? rand(5, 15)
                    : rand(0, 3);

                $dendaPerHari = match (strtolower($kategori->nama_kategori)) {
                    'kamera', 'elektronik' => 10000,
                    'proyektor'            => 15000,
                    'laptop'               => 20000,
                    default                => 2000,
                };

                if ($kondisi === 'Rusak') {
                    $dendaPerHari = 1000;
                }

                Alat::create([
                    'id_kategori'    => $kategori->id,
                    'nama_alat'      => $kategori->nama_kategori . ' ' . ($i),
                    'stok'           => $stok,
                    'kondisi'        => $kondisi,
                    'denda_per_hari' => $dendaPerHari,
                    'gambar'         => 'alat/default.png',
                ]);
            }
        }
    }
}