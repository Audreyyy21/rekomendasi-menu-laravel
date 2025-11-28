<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\MonthlyRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    // Tambahkan method ini di dalam class
    public function index()
    {
        // Ambil data rekomendasi terbaru
        // Kita gunakan with() agar query efisien (Eager Loading)
        $rekomendasi = \App\Models\MonthlyRecommendation::with([
            'menuTopDaging', 
            'menuTopJeroan', 
            'menuBottomDaging', 
            'menuBottomJeroan'
        ])->latest()->first();

        return view('rekomendasi.index', compact('rekomendasi'));
    }
    // UBAH NAMA FUNCTION DI SINI (dari generateRecommendation jadi generate)
    public function generate()
    {
        // 1. Tentukan Bulan yang mau dianalisis (Juni 2024 sesuai data CSV)
        $tahun = 2024;
        $bulan = 6;
        $formatBulan = "$tahun-" . str_pad($bulan, 2, '0', STR_PAD_LEFT); 

        // 2. Analisis Menu DAGING Terlaris (Top)
        $topDaging = Transaction::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->select('menu_daging_id', DB::raw('sum(jumlah_box) as total_jual'))
            ->groupBy('menu_daging_id')
            ->orderByDesc('total_jual')
            ->first();

        // 3. Analisis Menu JEROAN Terlaris (Top)
        $topJeroan = Transaction::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->select('menu_jeroan_id', DB::raw('sum(jumlah_box) as total_jual'))
            ->groupBy('menu_jeroan_id')
            ->orderByDesc('total_jual')
            ->first();

        // 4. Analisis Menu Kurang Laku (Bottom) - Daging
        $bottomDaging = Transaction::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->select('menu_daging_id', DB::raw('sum(jumlah_box) as total_jual'))
            ->groupBy('menu_daging_id')
            ->orderBy('total_jual', 'asc')
            ->first();

        // 5. Analisis Menu Kurang Laku (Bottom) - Jeroan
        $bottomJeroan = Transaction::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->select('menu_jeroan_id', DB::raw('sum(jumlah_box) as total_jual'))
            ->groupBy('menu_jeroan_id')
            ->orderBy('total_jual', 'asc')
            ->first();

        // Ambil Nama Menu untuk keperluan String Bundle
        // Kita butuh query sebentar ke tabel menus karena variabel $topDaging cuma punya ID
        $namaTopDaging = \App\Models\Menu::find($topDaging->menu_daging_id)->nama_menu ?? 'Daging';
        $namaTopJeroan = \App\Models\Menu::find($topJeroan->menu_jeroan_id)->nama_menu ?? 'Jeroan';
        $namaBottomDaging = \App\Models\Menu::find($bottomDaging->menu_daging_id)->nama_menu ?? 'Daging Lain';
        $namaBottomJeroan = \App\Models\Menu::find($bottomJeroan->menu_jeroan_id)->nama_menu ?? 'Jeroan Lain';

        // 6. Buat Bundle Rekomendasi
        $bundle = [
            'paket_1' => "Paket Puas ($namaTopDaging + $namaBottomJeroan)",
            'paket_2' => "Paket Hemat ($namaBottomDaging + $namaTopJeroan)",
            'alasan'  => "Kombinasi $namaTopDaging yang sedang tren dengan $namaBottomJeroan untuk menghabiskan stok."
        ];

        // 7. Simpan ke Database
        $rekomendasi = MonthlyRecommendation::updateOrCreate(
            ['bulan' => $formatBulan],
            [
                'top_daging_id' => $topDaging ? $topDaging->menu_daging_id : null,
                'top_jeroan_id' => $topJeroan ? $topJeroan->menu_jeroan_id : null,
                'bottom_daging_id' => $bottomDaging ? $bottomDaging->menu_daging_id : null,
                'bottom_jeroan_id' => $bottomJeroan ? $bottomJeroan->menu_jeroan_id : null,
                'rekomendasi_bundle' => $bundle,
                'status' => 'active'
            ]
        );
        $rekomendasi->load(['menuTopDaging', 'menuTopJeroan', 'menuBottomDaging', 'menuBottomJeroan']);

        return response()->json([
            'message' => 'Analisis Selesai',
            'data' => $rekomendasi
        ]);
    }
}