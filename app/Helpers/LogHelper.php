<?php

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

if (!function_exists('catat_log')) {
    function catat_log($aktivitas)
    {
        LogAktivitas::create([
            'id_user' => Auth::id() ?? null,
            'aktivitas' => $aktivitas,
            'waktu' => now(),
        ]);
    }
}