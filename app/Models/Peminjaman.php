<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'kode_peminjaman',
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'total_denda',
        'status_denda',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
    ];

    protected $casts = [
        'tgl_pinjam'   => 'date',
        'tgl_kembali'  => 'date',
        'total_denda'  => 'integer',
        'approved_at'  => 'datetime',
        'rejected_at'  => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($peminjaman) {

            $year = date('Y');

            $last = self::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            $number = $last
                ? ((int) substr($last->kode_peminjaman, -4)) + 1
                : 1;

            $peminjaman->kode_peminjaman =
                'PMJ-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

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
        return $this->pengembalian && $this->pengembalian->hari_telat > 0;
    }

    public function isExpired()
    {
        return $this->tgl_kembali < now()->toDateString();
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}