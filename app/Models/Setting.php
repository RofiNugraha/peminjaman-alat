<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = [
        'nama_aplikasi',
        'logo_ungu',
        'logo_putih',
        'hero_title',
        'hero_subtitle'
    ];
}