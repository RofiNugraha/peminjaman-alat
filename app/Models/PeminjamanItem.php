<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanItem extends Model
{
    protected $fillable = [
        'id_peminjaman',
        'id_alat',
        'qty'
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}