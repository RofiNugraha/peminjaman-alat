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
        Schema::create('pengembalian_items', function (Blueprint $table) {

            $table->id();
            $table->foreignId('id_pengembalian')->constrained('pengembalians')->cascadeOnDelete();
            $table->foreignId('id_alat')->constrained('alats');
            $table->integer('qty');
            $table->enum('kondisi', ['baik', 'rusak', 'hilang']);
            $table->integer('denda')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_items');
    }
};