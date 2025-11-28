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
        Schema::create('monthly_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 7); // Format: 2024-06
            
            // Menyimpan ID menu hasil analisis (Bisa Null jika data tidak cukup)
            $table->foreignId('top_daging_id')->nullable()->constrained('menus');
            $table->foreignId('top_jeroan_id')->nullable()->constrained('menus');
            $table->foreignId('bottom_daging_id')->nullable()->constrained('menus');
            $table->foreignId('bottom_jeroan_id')->nullable()->constrained('menus');
            
            // Simpan rekomendasi paket dalam JSON agar fleksibel
            // Contoh isi: {"popular": "Teriyaki + Gulai", "promo": "Semur + Sop"}
            $table->json('rekomendasi_bundle')->nullable();
            
            $table->enum('status', ['active', 'used', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_recommendations');
    }
};
