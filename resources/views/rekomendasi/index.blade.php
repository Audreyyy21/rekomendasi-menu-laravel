<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis Menu</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f9; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 4px; color: white; font-size: 0.8em; }
        .bg-green { background-color: #28a745; }
        .bg-red { background-color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .bundle-box { background: #e3f2fd; padding: 15px; border-radius: 5px; margin-top: 20px; border-left: 5px solid #2196f3; }
        .bundle-title { font-weight: bold; color: #0d47a1; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Laporan Rekomendasi Menu</h1>
    <p style="text-align: center; color: #666;">Periode Analisis: <strong>{{ $rekomendasi->bulan ?? '-' }}</strong></p>

    @if($rekomendasi)
        <table>
            <thead>
                <tr>
                    <th>Kategori Analisis</th>
                    <th>Menu Daging</th>
                    <th>Menu Jeroan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>🔥 Paling Laris (TOP)</strong></td>
                    <td>
                        {{ $rekomendasi->menuTopDaging->nama_menu }}
                        <span class="badge bg-green">Juara 1</span>
                    </td>
                    <td>
                        {{ $rekomendasi->menuTopJeroan->nama_menu }}
                        <span class="badge bg-green">Juara 1</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>📉 Kurang Laku (BOTTOM)</strong></td>
                    <td>
                        {{ $rekomendasi->menuBottomDaging->nama_menu }}
                        <span class="badge bg-red">Perlu Promo</span>
                    </td>
                    <td>
                        {{ $rekomendasi->menuBottomJeroan->nama_menu }}
                        <span class="badge bg-red">Perlu Promo</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="bundle-box">
            <div class="bundle-title">💡 Rekomendasi AI (Bundle Strategy)</div>
            <p><strong>Paket 1:</strong> {{ $rekomendasi->rekomendasi_bundle['paket_1'] }}</p>
            <p><strong>Paket 2:</strong> {{ $rekomendasi->rekomendasi_bundle['paket_2'] }}</p>
            <hr style="border: 0; border-top: 1px dashed #ccc;">
            <p><em>"{{ $rekomendasi->rekomendasi_bundle['alasan'] }}"</em></p>
        </div>
    @else
        <p style="text-align: center; color: red;">Belum ada data rekomendasi.</p>
    @endif
</div>

</body>
</html>