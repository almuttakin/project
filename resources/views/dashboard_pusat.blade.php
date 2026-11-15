<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0a3622">
    <title>Executive Monitoring Dashboard - Kepala TI PTPN Kantor Pusat Jakarta</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ptpn4.png') }}">

    <!-- CSS Vendor -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Script Vendor -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        :root {
            --ptpn-dark: #072a1a;
            --ptpn-green: #0b4629;
            --ptpn-emerald: #137547;
            --ptpn-mint: #2ebb77;
            --ptpn-gold: #cfa144;
            --ptpn-gold-light: #fef8eb;
            --ptpn-surface: #ffffff;
            --ptpn-bg: #f3f6f4;
            --ptpn-border: #e2e8e4;
            --ptpn-text: #1b2820;
            --ptpn-muted: #62746a;
        }

        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--ptpn-bg);
            color: var(--ptpn-text);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* ===== EXECUTIVE TOP HEADER ===== */
        .exec-header {
            background: linear-gradient(135deg, var(--ptpn-dark) 0%, var(--ptpn-green) 50%, var(--ptpn-emerald) 100%);
            color: #ffffff;
            padding: 24px 0 28px;
            box-shadow: 0 4px 20px rgba(7, 42, 26, 0.25);
            position: relative;
            overflow: hidden;
        }

        .exec-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(46, 187, 119, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .exec-badge-role {
            background: rgba(207, 161, 68, 0.2);
            border: 1px solid var(--ptpn-gold);
            color: #f7e7c4;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 30px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .exec-badge-live {
            background: rgba(46, 187, 119, 0.2);
            border: 1px solid var(--ptpn-mint);
            color: #bbf7d0;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background-color: #2ebb77;
            border-radius: 50%;
            box-shadow: 0 0 8px #2ebb77;
            animation: pulse-live 1.8s infinite;
        }

        @keyframes pulse-live {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 187, 119, 0.7); }
            70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(46, 187, 119, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 187, 119, 0); }
        }

        /* ===== METRIC CARDS ===== */
        .metric-card {
            background: var(--ptpn-surface);
            border: 1px solid var(--ptpn-border);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(11, 70, 41, 0.1);
            border-color: #bad3c5;
        }

        .metric-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: transparent;
            transition: background 0.3s;
        }

        .metric-card.accent-emerald::after { background: var(--ptpn-emerald); }
        .metric-card.accent-gold::after { background: var(--ptpn-gold); }
        .metric-card.accent-blue::after { background: #0284c7; }
        .metric-card.accent-purple::after { background: #7c3aed; }

        .metric-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .metric-val {
            font-size: 1.95rem;
            font-weight: 800;
            color: var(--ptpn-text);
            line-height: 1.15;
            letter-spacing: -0.5px;
        }

        .metric-sub {
            font-size: 0.85rem;
            color: var(--ptpn-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* ===== SECTION CARDS ===== */
        .section-card {
            background: var(--ptpn-surface);
            border: 1px solid var(--ptpn-border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--ptpn-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        /* ===== MAP CONTAINER ===== */
        #map-holding {
            height: 420px;
            width: 100%;
            border-radius: 12px;
            z-index: 1;
        }

        /* ===== TABLE STYLING ===== */
        .table-exec {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9rem;
        }

        .table-exec thead th {
            background-color: #f7faf8;
            color: var(--ptpn-dark);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border-bottom: 2px solid var(--ptpn-border);
        }

        .table-exec tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #edf2ef;
            vertical-align: middle;
        }

        .table-exec tbody tr:hover {
            background-color: #f4faf6;
        }

        .badge-pill-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* ===== PRINT MEDIA STYLES ===== */
        @media print {
            .no-print, .btn, .exec-header::before, #map-holding {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .section-card, .metric-card {
                border: 1px solid #ccc !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
            .exec-header {
                background: #0b4629 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- ================= EXECUTIVE HEADER ================= -->
    <header class="exec-header">
        <div class="container-fluid px-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="{{ asset('images/logo-ptpn4.png') }}" alt="PTPN Logo" height="48" class="bg-white p-1 rounded">
                        <div>
                            <span class="exec-badge-role">
                                <i class="bi bi-building"></i> AKTOR: KEPALA TI PTPN KANTOR PUSAT (JAKARTA)
                            </span>
                            <span class="exec-badge-live ms-2">
                                <span class="live-dot"></span> HOLDING ENTERPRISE GATEWAY
                            </span>
                        </div>
                    </div>
                    <h1 class="h3 fw-bold mb-1 text-white">
                        Executive Monitoring Dashboard — Konsolidasi Regional III
                    </h1>
                    <p class="text-white-50 mb-0 small">
                        Pusat Komando Teknologi Informasi Holding • Integrasi Penimbangan 12 Unit PKS ke Sistem ERP SAP Jakarta
                    </p>
                </div>
                <div class="col-lg-5 col-md-4 text-md-end mt-3 mt-md-0 no-print">
                    <div class="d-inline-flex flex-wrap gap-2 justify-content-md-end">
                        <button onclick="window.print()" class="btn btn-warning text-dark fw-bold btn-sm px-3 shadow-sm">
                            <i class="bi bi-printer-fill me-1"></i> Cetak Laporan Eksekutif
                        </button>
                        <a href="{{ route('bridge.index') }}" class="btn btn-outline-light btn-sm px-3" title="Beralih ke tampilan Admin TI Regional 3">
                            <i class="bi bi-arrow-left-right me-1"></i> Dashboard Admin Regional 3
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm px-3">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                    <div class="mt-2 text-white-50 small">
                        <i class="bi bi-clock me-1"></i> Data diperbarui: <span class="fw-semibold text-white">{{ date('d M Y - H:i:s') }} WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="container-fluid px-lg-5 py-4">

        <!-- Notifikasi Session -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- ===== ROW 1: 4 EXECUTIVE METRIC CARDS ===== -->
        <div class="row g-3 mb-4">
            <!-- Metric 1: Total Tonase Terkonsolidasi -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card accent-emerald">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase fw-bold text-muted small" style="letter-spacing: 0.5px;">Tonase Bersih (Netto)</div>
                            <div class="metric-val mt-2 text-success">
                                {{ number_format($totalNettoKg / 1000, 2, ',', '.') }} <span class="fs-5 text-muted fw-normal">Ton</span>
                            </div>
                            <div class="metric-sub">
                                <i class="bi bi-database me-1"></i> Total {{ number_format($totalNettoKg, 0, ',', '.') }} Kg TBS Terkonsolidasi
                            </div>
                        </div>
                        <div class="metric-icon-box bg-success-subtle text-success">
                            <i class="bi bi-truck-flatbed"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Tingkat Keberhasilan Integrasi SAP -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card accent-gold">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase fw-bold text-muted small" style="letter-spacing: 0.5px;">Sinkronisasi SAP ERP</div>
                            <div class="metric-val mt-2 text-warning" style="color: #b47d0e !important;">
                                {{ $sapSuccessRate }}%
                            </div>
                            <div class="metric-sub">
                                <i class="bi bi-send-check me-1"></i> <strong>{{ $totalSent }}</strong> terkirim / <strong>{{ $totalPending }}</strong> antrian pending
                            </div>
                        </div>
                        <div class="metric-icon-box bg-warning-subtle text-warning">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 3: SLA Gateway & Jaringan Holding -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card accent-blue">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase fw-bold text-muted small" style="letter-spacing: 0.5px;">SLA Uptime Gateway</div>
                            <div class="metric-val mt-2 text-info" style="color: #0284c7 !important;">
                                {{ $networkSla }}%
                            </div>
                            <div class="metric-sub">
                                <i class="bi bi-hdd-network me-1"></i> Gateway Linux VM • High Availability
                            </div>
                        </div>
                        <div class="metric-icon-box bg-info-subtle text-info">
                            <i class="bi bi-router"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 4: Node PKS Terhubung -->
            <div class="col-xl-3 col-md-6">
                <div class="metric-card accent-purple">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase fw-bold text-muted small" style="letter-spacing: 0.5px;">Monitoring PKS Regional</div>
                            <div class="metric-val mt-2 text-primary" style="color: #7c3aed !important;">
                                12 / 12 <span class="fs-5 text-muted fw-normal">Unit</span>
                            </div>
                            <div class="metric-sub">
                                <i class="bi bi-check-all me-1"></i> Total {{ $totalTrx }} Tiket Penimbangan TBS
                            </div>
                        </div>
                        <div class="metric-icon-box bg-purple-subtle text-primary" style="background-color: #ede9fe; color: #7c3aed;">
                            <i class="bi bi-buildings"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== ROW 2: GRAFIK & ANALISIS EKSEKUTIF ===== -->
        <div class="row g-4 mb-4">
            <!-- Chart 1: Tonase per PKS -->
            <div class="col-lg-8">
                <div class="section-card h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">
                            <i class="bi bi-bar-chart-line-fill text-success"></i>
                            Distribusi Tonase TBS Terkonsolidasi per Unit PKS Regional III
                        </div>
                        <span class="badge bg-light text-dark border">Satuan: Ton</span>
                    </div>
                    <div style="height: 320px;">
                        <canvas id="pksTonnageChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Proporsi Integrasi SAP ERP -->
            <div class="col-lg-4">
                <div class="section-card h-100 mb-0">
                    <div class="section-title mb-3">
                        <i class="bi bi-pie-chart-fill text-warning"></i>
                        Status Integrasi SAP Holding
                    </div>
                    <div style="height: 250px;" class="position-relative d-flex justify-content-center align-items-center">
                        <canvas id="sapStatusChart"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-top text-center small text-muted">
                        <span class="badge bg-success me-2">&bull; Terkirim: {{ $totalSent }}</span>
                        <span class="badge bg-warning text-dark">&bull; Menunggu / Sync: {{ $totalPending }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== ROW 3: PETA INTERAKTIF HOLDING DATA HUB ===== -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="section-card mb-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            Topologi Jaringan Data Hub Regional III &rarr; Data Center Kantor Pusat Jakarta
                        </div>
                        <div class="small text-muted">
                            <span class="badge bg-primary me-2"><i class="bi bi-building"></i> Data Center Jakarta</span>
                            <span class="badge bg-success me-2"><i class="bi bi-router"></i> Gateway Regional III</span>
                            <span class="badge bg-secondary"><i class="bi bi-cpu"></i> 12 Node PKS</span>
                        </div>
                    </div>
                    <div id="map-holding"></div>
                    <div class="mt-2 text-muted small d-flex justify-content-between">
                        <span><i class="bi bi-info-circle me-1"></i> Jalur transmisi mengalir dari Terminal Weighbridge PKS &rarr; Gateway Regional (Pekanbaru) &rarr; SAP ERP Server (Jakarta DC via VPN IP).</span>
                        <span class="fw-bold text-success">Jalur Backbone: AKTIF</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== ROW 4: TABEL RINGKASAN EKSEKUTIF 12 PKS ===== -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="section-card mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">
                            <i class="bi bi-table text-primary"></i>
                            Matriks Konsolidasi Performa 12 Unit PKS Terpantau
                        </div>
                        <div class="d-flex gap-2">
                            <input type="text" id="filterPksInput" class="form-control form-control-sm" placeholder="Cari PKS / Wilayah..." style="width: 220px;" onkeyup="filterPksTable()">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table-exec table" id="pksSummaryTable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Unit PKS</th>
                                    <th>Wilayah / Distrik</th>
                                    <th>IP Terminal VPN</th>
                                    <th class="text-center">Total Transaksi</th>
                                    <th class="text-end">Tonase Netto (Ton)</th>
                                    <th class="text-center">Integrasi SAP</th>
                                    <th class="text-center">Status VPN</th>
                                    <th class="text-center">Kepatuhan (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pksSummary as $idx => $pks)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $pks['nama'] }}</div>
                                        <div class="text-muted small">Kode: {{ $pks['kode'] }}</div>
                                    </td>
                                    <td>{{ $pks['wilayah'] }}</td>
                                    <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $pks['ip'] }}</code></td>
                                    <td class="text-center fw-bold">{{ $pks['total_trx'] }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($pks['netto_ton'], 2, ',', '.') }} Ton</td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-check2-circle me-1"></i>{{ $pks['sent_count'] }} Terkirim
                                        </span>
                                        @if($pks['pending_count'] > 0)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 ms-1">
                                            {{ $pks['pending_count'] }} Pending
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(in_array(strtolower($pks['status_vpn']), ['online', 'connected']))
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Online</span>
                                        @else
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Trouble</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pks['sync_percent'] }}%"></div>
                                            </div>
                                            <span class="fw-bold small">{{ $pks['sync_percent'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== ROW 5: AUDIT TRAIL TRANSAKSI TERVERIFIKASI SAP HOLDING ===== -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="section-card mb-0">
                    <div class="section-title mb-3">
                        <i class="bi bi-shield-check text-success"></i>
                        Audit Trail Transaksi Terverifikasi SAP ERP Holding (15 Data Terbaru)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle small mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Nota Transaksi</th>
                                    <th>Unit PKS</th>
                                    <th>No. Plat Kendaraan</th>
                                    <th class="text-end">Gross (Kg)</th>
                                    <th class="text-end">Tarra (Kg)</th>
                                    <th class="text-end">Netto (Kg)</th>
                                    <th class="text-center">Waktu Kirim SAP</th>
                                    <th class="text-center">Status Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sapVerifiedLedger as $row)
                                <tr>
                                    <td><code class="fw-bold text-dark">{{ $row->nota_no ?? 'NT-AUTO-'.str_pad($row->id, 4, '0', STR_PAD_LEFT) }}</code></td>
                                    <td><strong>{{ $row->pks_loc }}</strong></td>
                                    <td><span class="badge bg-light text-dark border">{{ $row->no_plat }}</span></td>
                                    <td class="text-end">{{ number_format($row->gross, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->tarra, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($row->netto, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $row->sap_sent_at ? date('d/m/Y H:i:s', strtotime($row->sap_sent_at)) : date('d/m/Y H:i:s') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi SAP
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Belum ada data transaksi yang berstatus terkirim ke SAP.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white border-top py-3 text-center text-muted small mt-5 no-print">
        <div class="container-fluid">
            &copy; {{ date('Y') }} PT Perkebunan Nusantara IV (PTPN IV) PalmCo &bull; Divisi Teknologi Informasi Kantor Pusat Jakarta &bull; AutoXML Gateway v2.4
        </div>
    </footer>

    <!-- ================= JAVASCRIPT ================= -->
    <script>
        // Data dari Controller
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartTonase = {!! json_encode($chartTonase) !!};
        const totalSent = {{ $totalSent }};
        const totalPending = {{ $totalPending }};
        const pksData = {!! json_encode($pksSummary) !!};

        // 1. Inisialisasi Chart Tonase per PKS
        const ctxBar = document.getElementById('pksTonnageChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Tonase Bersih (Ton)',
                    data: chartTonase,
                    backgroundColor: 'rgba(19, 117, 71, 0.85)',
                    borderColor: '#0b4629',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Tonase (Ton)' }
                    },
                    x: {
                        ticks: { maxRotation: 45, minRotation: 45 }
                    }
                }
            }
        });

        // 2. Inisialisasi Donut Chart Status SAP
        const ctxDonut = document.getElementById('sapStatusChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Terkirim SAP', 'Menunggu / Pending'],
                datasets: [{
                    data: [totalSent, totalPending],
                    backgroundColor: ['#137547', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 3. Inisialisasi Leaflet Map
        const map = L.map('map-holding').setView([1.5, 102.5], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors | PTPN Holding IT'
        }).addTo(map);

        // Marker Kantor Pusat Jakarta
        const jktLat = -6.1751, jktLng = 106.8272;
        const jktIcon = L.divIcon({
            html: '<div style="background:#dc2626;color:#fff;border-radius:50%;width:34px;height:34px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 12px rgba(220,38,38,0.8);font-size:16px;"><i class="bi bi-building"></i></div>',
            className: '',
            iconSize: [34, 34],
            iconAnchor: [17, 17]
        });
        L.marker([jktLat, jktLng], { icon: jktIcon }).addTo(map)
            .bindPopup('<b>Kantor Pusat PTPN Holding (Jakarta)</b><br>Data Center & Server ERP SAP Pusat')
            .openPopup();

        // Marker Regional III Gateway (Pekanbaru)
        const pbrLat = 0.5071, pbrLng = 101.4478;
        const pbrIcon = L.divIcon({
            html: '<div style="background:#0284c7;color:#fff;border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(2,132,199,0.8);font-size:14px;"><i class="bi bi-router"></i></div>',
            className: '',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        L.marker([pbrLat, pbrLng], { icon: pbrIcon }).addTo(map)
            .bindPopup('<b>Gateway Regional III (Pekanbaru)</b><br>Debian Linux VM Integration Bridge');

        // Garis Koneksi Backbone Regional 3 -> Jakarta
        L.polyline([[pbrLat, pbrLng], [jktLat, jktLng]], {
            color: '#dc2626',
            weight: 3,
            dashArray: '8, 8',
            opacity: 0.8
        }).addTo(map).bindPopup('<b>Backbone VPN Trunk Holding</b><br>Regional III &harr; Jakarta DC');

        // Marker 12 Unit PKS
        pksData.forEach(p => {
            const pksIcon = L.divIcon({
                html: '<div style="background:#137547;color:#fff;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 6px rgba(19,117,71,0.7);font-size:11px;"><i class="bi bi-cpu"></i></div>',
                className: '',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            const m = L.marker([p.lat, p.lng], { icon: pksIcon }).addTo(map);
            m.bindPopup(`<b>${p.nama}</b><br>${p.wilayah}<br>IP: ${p.ip}<br>Tonase: <b>${p.netto_ton} Ton</b><br>Status VPN: <b>${p.status_vpn}</b>`);

            // Garis koneksi PKS ke Gateway Pekanbaru
            L.polyline([[p.lat, p.lng], [pbrLat, pbrLng]], {
                color: '#137547',
                weight: 1.5,
                opacity: 0.5
            }).addTo(map);
        });

        // 4. Filter Tabel PKS
        function filterPksTable() {
            const input = document.getElementById('filterPksInput').value.toLowerCase();
            const rows = document.querySelectorAll('#pksSummaryTable tbody tr');
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                r.style.display = text.includes(input) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
