<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('id_user')->constrained('users');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dibatalkan', 'kadaluarsa', 'dikembalikan'])->default('menunggu');
            $table->integer('total_denda')->default(0);
            $table->enum('status_denda', ['tidak_ada','belum','lunas'])->default('tidak_ada');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};