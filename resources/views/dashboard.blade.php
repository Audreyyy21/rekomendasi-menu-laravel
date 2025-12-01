<!DOCTYPE html>
<html>
<head>
    <title>Dashboard — Sistem Rekomendasi Akikah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #e8eaed;
            font-family: "Segoe UI", sans-serif;
        }

        /* BOX LATAR BELAKANG BESAR */
        .dashboard-wrapper {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .section-title {
            font-weight: 700;
            font-size: 28px;
            text-align: center;
            margin-bottom: 25px;
            color: #2b2b2b;
        }

        /* RINGKASAN STAT BOX */
        .stat-box {
            border-radius: 14px;
            padding: 18px 20px;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .bg-gradient-blue {
            background: linear-gradient(135deg, #4a90e2, #1c6dd0);
        }
        .bg-gradient-green {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }
        .bg-gradient-orange {
            background: linear-gradient(135deg, #f39c12, #d68910);
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-top: -4px;
        }

        /* Card Custom */
        .card-custom {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        }
        .card-header {
            font-weight: 600;
        }

        /* Buttons */
        .btn-lg {
            padding: 11px 26px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="dashboard-wrapper">

    <h2 class="section-title">Dashboard Sistem Rekomendasi Akikah</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- RINGKASAN STAT -->
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="stat-box bg-gradient-blue">
                <div class="stat-label">Total Menu</div>
                <div class="stat-value">{{ $totalMenu ?? 0 }}</div>
            </div>
        </div>

        <div class="col-md-4 mt-3 mt-md-0">
            <div class="stat-box bg-gradient-green">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $totalTransaksi ?? 0 }}</div>
            </div>
        </div>

        <div class="col-md-4 mt-3 mt-md-0">
            <div class="stat-box bg-gradient-orange">
                <div class="stat-label">Rekomendasi Dibuat</div>
                <div class="stat-value">{{ $totalRekomendasi ?? 0 }}</div>
            </div>
        </div>

    </div>

    <!-- CARD TOP MENU PALING LAKU -->
    <div class="card card-custom mb-4">
        <div class="card-header bg-primary text-white">
            Top 2 Menu Paling Laku
        </div>
        <ul class="list-group list-group-flush">
            @forelse($top2menu as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $item->nama_menu }}</span>
                    <span class="badge bg-primary">{{ $item->total_penjualan }} box</span>
                </li>
            @empty
                <li class="list-group-item text-muted">Belum ada transaksi.</li>
            @endforelse
        </ul>
    </div>

    <!-- CARD TOP BUNDLING -->
<div class="card card-custom mb-4">
    <div class="card-header bg-success text-white">
        Rekomendasi Bundling Terbaru
    </div>
    <div class="card-body">
        @forelse($top2bundling as $rec)
            <p>{{ $rec['alasan'] ?? 'Belum ada rekomendasi.' }}</p>
        @empty
            <p class="text-muted">Belum ada rekomendasi dibuat.</p>
        @endforelse
    </div>
</div>


    <!-- BUTTONS -->
    <div class="text-center mt-4">
        <a href="/rekomendasi" class="btn btn-warning btn-lg px-4">
            Proses Rekomendasi
        </a>
        <a href="/logout" class="btn btn-danger btn-lg ms-2 px-4">
            Logout
        </a>
    </div>

</div>

</body>
</html>
