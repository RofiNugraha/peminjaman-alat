<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'otp',
        'otp_expired_at'
    ];

    protected $hidden = ['password','otp'];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'peminjam';
            }
        });
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_user');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_user');
    }

    public function pengembalians()
    {
        return $this->hasMany(Pengembalian::class, 'id_petugas');
    }

    public function profilSiswa()
    {
        return $this->hasOne(ProfilSiswa::class);
    }
    
    public function dataSiswa()
    {
        return $this->hasOneThrough(DataSiswa::class, ProfilSiswa::class,
            'user_id', // FK di profil_siswas
            'nisn',    // FK di data_siswas
            'id',      // PK di users
            'nisn'     // FK di profil_siswas
        );
    }
}