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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            
            // Relasi ke tabel menus
            // Menggunakan cascadeOnDelete agar jika menu dihapus, transaksi aman (atau bisa null)
            $table->foreignId('menu_daging_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('menu_jeroan_id')->constrained('menus')->onDelete('cascade');
            
            $table->integer('jumlah_box');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
