<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianItem extends Model
{
    protected $table = 'pengembalian_items';

    protected $fillable = [
        'id_pengembalian',
        'id_alat',
        'qty_baik',
        'qty_hilang',
        'qty_rusak',
        'denda',
    ];

    protected $casts = [
        'denda' => 'integer',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }

    public function kategoris()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function pengembalians()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }
}