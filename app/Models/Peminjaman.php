<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'total_denda',
        'status_denda',
    ];

    protected $casts = [
        'tgl_pinjam'   => 'date',
        'tgl_kembali'  => 'date',
        'total_denda'  => 'integer',
    ];

    public function canBeApproved()
    {
        return $this->status === 'menunggu';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function items()
    {
        return $this->hasMany(PeminjamanItem::class, 'id_peminjaman');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }

    public function getIsTelatAttribute(): bool
    {
        return $this->status === 'dikembalikan'
            && $this->pengembalian
            && $this->pengembalian->hari_telat > 0;
    }

    public function getStatusDendaLabelAttribute(): string
    {
        return match ($this->status_denda) {
            'belum'      => 'Belum Dibayar',
            'lunas'      => 'Lunas',
            default      => 'Tidak Ada',
        };
    }
}