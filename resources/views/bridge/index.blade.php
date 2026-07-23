<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Timbangan PKS & SAP Integration - PTPN 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f3f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-ptpn { background: linear-gradient(90deg, #0f5132 0%, #198754 100%); }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .badge-trouble { background-color: #dc3545; color: white; }
        .badge-ok { background-color: #198754; color: white; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-ptpn mb-4 shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-link navbar-brand fw-bold text-white" href="#">
                <i class="bi bi-truck me-2"></i> PTPN 4 REGIONAL - Sistem Monitoring Timbangan PKS & SAP XML
            </a>
            <span class="badge bg-light text-success fw-bold px-3 py-2">Mode: In-Memory / No DB</span>
        </div>
    </nav>

    <div class="container-fluid px-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Banner Monitoring / Historical Problem -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card card-custom p-3 border-start border-danger border-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold text-uppercase">Analisis Timbangan Sering Mati</small>
                            <h5 class="fw-bold text-danger mt-1 mb-0">PKS Sawit Seberang</h5>
                            <small class="text-muted"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Terdeteksi 3x offline minggu ini</small>
                        </div>
                        <div class="bg-danger-subtle text-danger p-3 rounded-circle">
                            <i class="bi bi-display-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-custom p-3 border-start border-warning border-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold text-uppercase">Analisis VPN Sering Trouble</small>
                            <h5 class="fw-bold text-warning mt-1 mb-0">VPN IP: 10.14.2.10 (PKS Pabatu)</h5>
                            <small class="text-muted"><i class="bi bi-wifi-off text-danger"></i> Sering RTO saat sync ke SAP</small>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                            <i class="bi bi-router-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Form Input Timbangan -->
            <div class="col-lg-4">
                <div class="card card-custom p-4 bg-white">
                    <h5 class="fw-bold mb-3 text-success"><i class="bi bi-plus-circle me-2"></i>Input Transaksi Timbangan</h5>
                    <form action="{{ route('bridge.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">No. Plat Kendaraan (Plat BM)</label>
                            <input type="text" name="no_plat" class="form-control" placeholder="Contoh: BM 1234 XX" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Lokasi PKS</label>
                            <select name="pks_loc" class="form-select" required>
                                <option value="PKS Pabatu">PKS Pabatu</option>
                                <option value="PKS Sawit Seberang">PKS Sawit Seberang</option>
                                <option value="PKS Bah Jambi">PKS Bah Jambi</option>
                                <option value="PKS Mayang">PKS Mayang</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">IP Address VPN</label>
                            <input type="text" name="vpn_ip" class="form-control" placeholder="10.14.x.x" value="10.14.2.10" required>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Jam Keluar</label>
                                <input type="time" name="jam_keluar" class="form-control" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Gross / Masuk (Kg)</label>
                                <input type="number" name="gross" class="form-control" placeholder="25000" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Tarra / Keluar (Kg)</label>
                                <input type="number" name="tarra" class="form-control" placeholder="9000" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-secondary mb-2">Status Alat & Jaringan</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Kondisi Timbangan</label>
                                <select name="status_timbangan" class="form-select">
                                    <option value="Normal">Normal</option>
                                    <option value="Mati/Offline">Mati/Offline</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Kondisi VPN</label>
                                <select name="status_vpn" class="form-select">
                                    <option value="Connected">Connected</option>
                                    <option value="Trouble">Trouble</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-semibold"><i class="bi bi-save me-1"></i> Simpan Data</button>
                    </form>
                </div>
            </div>

            <!-- Tabel Data & Generate XML -->
            <div class="col-lg-8">
                <div class="card card-custom p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-table me-2"></i>Data Timbangan & Export SAP Config</h5>
                        <span class="badge bg-secondary">{{ count($logs) }} Data Terdaftar</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr class="text-secondary small text-uppercase">
                                    <th>No. Plat</th>
                                    <th>PKS / VPN IP</th>
                                    <th>Masuk / Keluar</th>
                                    <th>Netto (Kg)</th>
                                    <th>Status Alat</th>
                                    <th>Status VPN</th>
                                    <th class="text-center">Aksi SAP XML</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $log['no_plat'] }}</span>
                                        </td>
                                        <td>
                                            <small class="d-block fw-semibold">{{ $log['pks_loc'] }}</small>
                                            <small class="text-muted"><i class="bi bi-ethernet"></i> {{ $log['vpn_ip'] }}</small>
                                        </td>
                                        <td>
                                            <small class="d-block"><i class="bi bi-clock"></i> In: {{ $log['jam_masuk'] }}</small>
                                            <small class="text-muted"><i class="bi bi-clock-history"></i> Out: {{ $log['jam_keluar'] }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($log['netto']) }} Kg</span>
                                            <small class="d-block text-muted">Gross: {{ number_format($log['gross']) }}</small>
                                        </td>
                                        <td>
                                            @if($log['status_timbangan'] == 'Normal')
                                                <span class="badge badge-ok"><i class="bi bi-check-circle"></i> Normal</span>
                                            @else
                                                <span class="badge badge-trouble"><i class="bi bi-x-circle"></i> Offline</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log['status_vpn'] == 'Connected')
                                                <span class="badge badge-ok">Connected</span>
                                            @else
                                                <span class="badge badge-trouble">Trouble</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('bridge.xml', $log['id']) }}" class="btn btn-sm btn-outline-primary mb-1" title="Download Auto XML Config untuk SAP">
                                                <i class="bi bi-filetype-xml"></i> XML SAP
                                            </a>
                                            <a href="{{ route('bridge.delete', $log['id']) }}" class="btn btn-sm btn-outline-danger mb-1" onclick="return confirm('Hapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data timbangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>