<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSiswa extends Model
{
    protected $table = 'profil_siswas';

    protected $fillable = [
        'user_id',
        'nisn',
        'no_hp',
        'no_hp_ortu',
        'alamat',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dataSiswa()
    {
        return $this->belongsTo(DataSiswa::class, 'nisn', 'nisn');
    }
}