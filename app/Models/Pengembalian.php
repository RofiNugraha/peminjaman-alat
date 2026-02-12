<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalians';

    protected $fillable = [
        'id_peminjaman',
        'tgl_dikembalikan',
        'hari_telat',
    ];

    protected $casts = [
        'tgl_dikembalikan' => 'date',
        'hari_telat'       => 'integer',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function denda()
    {
        return $this->hasOne(Denda::class, 'id_pengembalian');
    }
}