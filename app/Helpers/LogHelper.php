<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

if (!function_exists('logAktivitas')) {
    function logAktivitas(string $aksi, string $fitur, string $detail = null, $userId = null): void
    {
        try {
            $idUser = $userId ?? Auth::id();

            if (!$idUser) {
                Log::warning('Log aktivitas gagal: user tidak terautentikasi');
                return;
            }

            $pesan = strtoupper($aksi) . ' - ' . $fitur;

            if ($detail) {
                $pesan .= ' - ' . $detail;
            }

            DB::table('log_aktivitas')->insert([
                'id_user'   => $idUser,
                'aktivitas' => $pesan,
                'waktu'     => now(),
            ]);

        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan log aktivitas: ' . $e->getMessage());
        }
    }
}