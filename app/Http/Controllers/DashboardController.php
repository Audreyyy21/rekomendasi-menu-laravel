<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use App\Models\MonthlyRecommendation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * ===============================
         * 1. HITUNG TOP 2 MENU PALING LARIS
         * ===============================
         */

        // Penjualan menu daging
        $penjualanDaging = DB::table('transactions')
            ->select('menu_daging_id as menu_id')
            ->selectRaw('SUM(jumlah_box) as total_penjualan')
            ->groupBy('menu_daging_id');

        // Penjualan menu jeroan
        $penjualanJeroan = DB::table('transactions')
            ->select('menu_jeroan_id as menu_id')
            ->selectRaw('SUM(jumlah_box) as total_penjualan')
            ->groupBy('menu_jeroan_id');

        // Gabungkan keduanya lalu ambil top 2
        $top2menu = $penjualanDaging
            ->unionAll($penjualanJeroan)
            ->get()
            ->groupBy('menu_id')
            ->map(function ($rows) {
                return [
                    'menu_id' => $rows->first()->menu_id,
                    'total_penjualan' => $rows->sum('total_penjualan'),
                ];
            })
            ->sortByDesc('total_penjualan')
            ->take(2)
            ->map(function ($row) {
                $row['nama_menu'] = Menu::find($row['menu_id'])->nama_menu ?? 'Menu Tidak Ditemukan';
                return (object) $row;
            });


        /**
         * ===============================
         * 2. TOP 2 REKOMENDASI BUNDLING TERBARU
         * ===============================
         */
        $top2bundling = MonthlyRecommendation::orderBy('created_at', 'desc')
    ->take(2)
    ->get()
    ->map(function ($item) {
        $bundle = is_string($item->rekomendasi_bundle)
            ? json_decode($item->rekomendasi_bundle, true)
            : $item->rekomendasi_bundle;

        return [
            'alasan' => $bundle['alasan'] ?? '-',
        ];
    });


        /**
         * ===============================
         * 3. DATA SUMMARY UNTUK DASHBOARD
         * ===============================
         */
        $totalMenu = Menu::count();
        $totalTransaksi = Transaction::count();
        $totalRekomendasi = MonthlyRecommendation::count();


        /**
         * ===============================
         * RETURN KE VIEW
         * ===============================
         */
        return view('dashboard', compact(
            'top2menu',
            'top2bundling',
            'totalMenu',
            'totalTransaksi',
            'totalRekomendasi'
        ));
    }
}
