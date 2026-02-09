<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'id_kategori');
    }
}