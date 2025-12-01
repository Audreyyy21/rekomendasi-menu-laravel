<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\MonthlyRecommendation;
use Carbon\Carbon;

class RecommendationController extends Controller
{
    /**
     * HALAMAN UTAMA REKOMENDASI
     */
    public function index()
    {
        $rekomendasi = MonthlyRecommendation::with([
            'menuTopDaging',
            'menuTopJeroan',
            'menuBottomDaging',
            'menuBottomJeroan'
        ])->latest()->first();

        return view('rekomendasi.index', compact('rekomendasi'));
    }

    /**
     * GENERATE REKOMENDASI
     */
    public function generate()
    {
        $bulan = Carbon::now()->format('Y-m');

        // Hitung menu daging paling laris
        $topDaging = Transaction::selectRaw('menu_daging_id, SUM(jumlah_box) AS total')
            ->groupBy('menu_daging_id')
            ->orderByDesc('total')
            ->first();

        // Hitung menu jeroan paling laris
        $topJeroan = Transaction::selectRaw('menu_jeroan_id, SUM(jumlah_box) AS total')
            ->groupBy('menu_jeroan_id')
            ->orderByDesc('total')
            ->first();

        // Hitung menu daging paling rendah
        $bottomDaging = Transaction::selectRaw('menu_daging_id, SUM(jumlah_box) AS total')
            ->groupBy('menu_daging_id')
            ->orderBy('total')
            ->first();

        // Hitung menu jeroan paling rendah
        $bottomJeroan = Transaction::selectRaw('menu_jeroan_id, SUM(jumlah_box) AS total')
            ->groupBy('menu_jeroan_id')
            ->orderBy('total')
            ->first();

        // Ambil nama menu berdasarkan ID
        $topDagingMenu  = Menu::find($topDaging->menu_daging_id ?? null);
        $topJeroanMenu  = Menu::find($topJeroan->menu_jeroan_id ?? null);
        $bottomDagingMenu = Menu::find($bottomDaging->menu_daging_id ?? null);
        $bottomJeroanMenu = Menu::find($bottomJeroan->menu_jeroan_id ?? null);

        // Logika AI Bundling
        $bundle = [
            'paket_1' => $topDagingMenu?->nama_menu . " + " . $topJeroanMenu?->nama_menu,
            'paket_2' => $bottomDagingMenu?->nama_menu . " + " . $bottomJeroanMenu?->nama_menu,
            'alasan'  => "Paket 1 menggabungkan menu paling laris untuk meningkatkan profit. 
                          Paket 2 menggabungkan menu yang kurang laku untuk menaikkan minat pembelian."
        ];

        // Simpan ke database
        MonthlyRecommendation::create([
            'bulan' => $bulan,
            'top_daging_id' => $topDagingMenu?->id,
            'top_jeroan_id' => $topJeroanMenu?->id,
            'bottom_daging_id' => $bottomDagingMenu?->id,
            'bottom_jeroan_id' => $bottomJeroanMenu?->id,
            'rekomendasi_bundle' => $bundle
        ]);

        return redirect('/rekomendasi')->with('success', 'Rekomendasi berhasil dibuat!');
    }

    /**
     * HALAMAN HISTORY
     */
    public function history()
    {
        $history = MonthlyRecommendation::with([
            'menuTopDaging',
            'menuTopJeroan',
            'menuBottomDaging',
            'menuBottomJeroan'
        ])->orderBy('bulan', 'desc')->get();

        return view('rekomendasi.history', compact('history'));
    }
}
