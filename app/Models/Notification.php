<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id_user',
        'judul',
        'pesan',
        'dibaca',
        'notifiable_id',
        'notifiable_type',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function getUrlAttribute()
    {
        if (!$this->notifiable) return null;

        if ($this->notifiable instanceof \App\Models\Peminjaman) {
            return route('peminjam.denda.show', $this->notifiable->id);
        }

        return null;
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}