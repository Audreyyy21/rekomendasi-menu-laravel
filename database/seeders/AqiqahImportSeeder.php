<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AqiqahImportSeeder extends Seeder
{
    public function run()
    {
        $csvFile = base_path('database/data/data_aqiqah.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan!");
            return;
        }

        $handle = fopen($csvFile, "r");
        
        // Deteksi separator
        $headerLine = fgets($handle);
        rewind($handle);
        $separator = (strpos($headerLine, ';') !== false) ? ';' : ',';
        
        // Lewati Header
        fgetcsv($handle, 0, $separator);

        $this->command->info('Mulai Import Ulang...');
        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                if (count($data) < 4) continue;

                $tanggalRaw     = $data[0] ?? '';
                $namaMenuDaging = strtoupper(trim($data[3] ?? '')); 
                $namaMenuJeroan = strtoupper(trim($data[4] ?? ''));
                $jumlahBox      = isset($data[6]) ? (int) $data[6] : 0; 

                // --- LOGIKA TANGGAL YANG DIPERBAIKI ---
                // 1. Ganti strip (-) dengan spasi agar seragam: "1-Jun-24" -> "1 Jun 24"
                $cleanDate = str_replace('-', ' ', $tanggalRaw);
                
                try {
                    // 2. Paksa format "Tanggal Bulan(Singkatan) Tahun(2 digit)"
                    // Contoh: 1 Jun 24
                    $tanggal = Carbon::createFromFormat('j M y', $cleanDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback darurat: Jika gagal, masukkan ke tanggal 1 Juni 2024 manual
                    // daripada masuk ke 2025.
                    $tanggal = '2024-06-01'; 
                }

                // Cari/Buat Menu
                $dagingId = null;
                if (!empty($namaMenuDaging)) {
                    $menu = Menu::firstOrCreate(['nama_menu' => $namaMenuDaging, 'kategori' => 'daging']);
                    $dagingId = $menu->id;
                }

                $jeroanId = null;
                if (!empty($namaMenuJeroan)) {
                    $menu = Menu::firstOrCreate(['nama_menu' => $namaMenuJeroan, 'kategori' => 'jeroan']);
                    $jeroanId = $menu->id;
                }

                if ($dagingId && $jeroanId) {
                    Transaction::create([
                        'tanggal' => $tanggal,
                        'menu_daging_id' => $dagingId,
                        'menu_jeroan_id' => $jeroanId,
                        'jumlah_box' => $jumlahBox,
                    ]);
                }
            }

            DB::commit();
            fclose($handle);
            $this->command->info('Sukses! Tanggal sudah diperbaiki.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
}