<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRecommendation extends Model
{
    use HasFactory;

    // 1. Tambahkan $fillable agar bisa di-input
    protected $fillable = [
        'bulan',
        'top_daging_id',
        'top_jeroan_id',
        'bottom_daging_id',
        'bottom_jeroan_id',
        'rekomendasi_bundle',
        'status'
    ];

    // 2. (Opsional tapi PENTING) Cast JSON agar otomatis jadi Array saat diambil
    protected $casts = [
        'rekomendasi_bundle' => 'array',
    ];

    // 3. Relasi (Jika nanti butuh menampilkan nama menu)
    public function menuTopDaging()
    {
        return $this->belongsTo(Menu::class, 'top_daging_id');
    }

    public function menuTopJeroan()
    {
        return $this->belongsTo(Menu::class, 'top_jeroan_id');
    }

    public function menuBottomDaging()
    {
        return $this->belongsTo(Menu::class, 'bottom_daging_id');
    }

    public function menuBottomJeroan()
    {
        return $this->belongsTo(Menu::class, 'bottom_jeroan_id');
    }
}