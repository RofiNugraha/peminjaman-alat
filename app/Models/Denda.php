<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $table = 'dendas';

    protected $fillable = [
        'id_pengembalian',
        'tarif_per_hari',
        'total_denda',
        'status',
    ];

    protected $casts = [
        'tarif_per_hari' => 'integer',
        'total_denda'    => 'integer',
    ];
    
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum_ditindak' => 'Belum Ditindak',
            'diingatkan'     => 'Sudah Diingatkan',
            'dibayar'        => 'Sudah Dibayar',
            default          => '-',
        };
    }
}