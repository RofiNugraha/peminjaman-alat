<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alats';

    protected $fillable = [
        'kode_alat',
        'id_kategori',
        'nama_alat',
        'stok',
        'gambar',
        'denda_per_hari',
    ];

    protected static function booted()
    {
        static::creating(function ($alat) {

            $last = self::orderBy('id', 'desc')->first();
            $number = $last ? ((int) substr($last->kode_alat, -3)) + 1 : 1;

            $alat->kode_alat = 'ALT-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    public function kategoris()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_alat');
    }

    public function peminjamanItems()
    {
        return $this->hasMany(PeminjamanItem::class, 'id_alat');
    }

    public function pengembalianItems()
    {
        return $this->hasMany(PengembalianItem::class, 'id_alat');
    }
}