<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSiswa extends Model
{
    protected $table = 'data_siswas';

    protected $fillable = [
        'nisn',
        'nama',
        'kelas',
        'jurusan',
        'tahun_angkatan',
        'tahun_ajaran',
        'status',
    ];

    public function profilSiswa()
    {
        return $this->hasOne(ProfilSiswa::class, 'nisn', 'nisn');
    }
}