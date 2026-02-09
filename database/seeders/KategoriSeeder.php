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
            'Laptop',
            'Proyektor',
            'Kamera',
            'Printer',
            'Scanner',
            'Speaker',
            'Microphone',
            'Monitor',
            'Keyboard',
            'Mouse',
            'Router',
            'Switch Jaringan',
            'Harddisk',
            'Flashdisk',
            'UPS',
            'Tablet',
            'Smartphone',
            'Tripod',
            'Lighting',
            'Headset',
            'Kabel HDMI',
            'Kabel LAN',
            'Adaptor',
            'Charger',
            'Power Supply',
            'CPU',
            'Motherboard',
            'RAM',
            'VGA Card',
            'SSD',
        ];

        foreach ($kategoris as $nama) {
            Kategori::create([
                'nama_kategori' => $nama,
                'keterangan' => 'Kategori untuk perangkat ' . strtolower($nama),
            ]);
        }
    }
}