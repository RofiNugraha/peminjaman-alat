<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alats';

    protected $fillable = [
        'id_kategori',
        'nama_alat',
        'stok',
        'kondisi',
        'gambar',
        'denda_per_hari',
        'denda_per_hari',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_alat');
    }

    public function items()
    {
        return $this->hasMany(PeminjamanItem::class, 'id_alat');
    }
}