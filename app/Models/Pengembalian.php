<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalians';

    protected $fillable = [
        'id_peminjaman',
        'id_petugas',
        'tgl_dikembalikan',
        'hari_telat',
        'denda_telat',
    ];

    protected $casts = [
        'tgl_dikembalikan' => 'date',
        'hari_telat'       => 'integer',
        'denda_telat'      => 'integer',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }

    public function items()
    {
        return $this->hasMany(PengembalianItem::class, 'id_pengembalian');
    }
}