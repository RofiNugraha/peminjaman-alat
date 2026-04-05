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
            $jumlahAlat = rand(3,6);

            for ($i=1; $i <= $jumlahAlat; $i++) {
                $denda = match(strtolower($kategori->nama_kategori)){
                    'kamera','elektronik' => 10000,
                    'proyektor' => 15000,
                    'laptop' => 20000,
                    default => 2000
                };

                Alat::create([
                    'id_kategori'=>$kategori->id,
                    'nama_alat'=>$kategori->nama_kategori.' '.$i,
                    'stok'=>rand(5,15),
                    'denda_per_hari'=>$denda,
                    'gambar'=>'alat/default.png'
                ]);
            }
        }
    }
}