<?php

namespace App\Http\Controllers;

use App\Models\UnitPks;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan Halaman Utama / Landing Page Publik
     */
    public function index()
    {
        // 1. Ambil data unit PKS dari database jika ada
        $dbUnits = collect();
        try {
            $dbUnits = UnitPks::all();
        } catch (\Throwable $e) {
            // Fallback graceful jika database belum termigrasi/terhubung
        }

        // 2. Data default unit PKS (12 unit mencakup 5 unit seeder + unit PKS Regional III)
        $defaultUnits = [
            [
                'id' => 1,
                'nama_unit'   => 'PKS Pabatu',
                'lokasi'      => 'Kec. Tebing Tinggi, Kab. Serdang Bedagai',
                'ip_vpn'      => '10.14.2.10',
                'ip_wifi'     => '192.168.2.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 3.3150,
                'lng'         => 99.1620,
            ],
            [
                'id' => 2,
                'nama_unit'   => 'PKS Sawit Seberang',
                'lokasi'      => 'Kec. Sawit Seberang, Kab. Langkat',
                'ip_vpn'      => '10.14.3.15',
                'ip_wifi'     => '192.168.3.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 3.6820,
                'lng'         => 98.2430,
            ],
            [
                'id' => 3,
                'nama_unit'   => 'PKS Bah Jambi',
                'lokasi'      => 'Kec. Jawa Maraja Bah Jambi, Kab. Simalungun',
                'ip_vpn'      => '10.14.4.22',
                'ip_wifi'     => '192.168.4.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 2.9810,
                'lng'         => 99.1360,
            ],
            [
                'id' => 4,
                'nama_unit'   => 'PKS Mayang',
                'lokasi'      => 'Kec. Bosar Maligas, Kab. Simalungun',
                'ip_vpn'      => '10.14.5.8',
                'ip_wifi'     => '192.168.5.50',
                'status_vpn'  => 'Trouble',
                'status_wifi' => 'Online',
                'lat'         => 3.0850,
                'lng'         => 99.3210,
            ],
            [
                'id' => 5,
                'nama_unit'   => 'PKS Tanah Hitam',
                'lokasi'      => 'Kec. Lima Puluh, Kab. Batu Bara',
                'ip_vpn'      => '10.14.6.12',
                'ip_wifi'     => '192.168.6.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 3.1670,
                'lng'         => 99.5120,
            ],
            [
                'id' => 6,
                'nama_unit'   => 'PKS Sei Galuh',
                'lokasi'      => 'Kec. Tapung, Kab. Kampar, Riau',
                'ip_vpn'      => '10.14.7.18',
                'ip_wifi'     => '192.168.7.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 0.6180,
                'lng'         => 101.3200,
            ],
            [
                'id' => 7,
                'nama_unit'   => 'PKS Sei Pagar',
                'lokasi'      => 'Kec. Kampar Kiri Hilir, Kab. Kampar, Riau',
                'ip_vpn'      => '10.14.8.25',
                'ip_wifi'     => '192.168.8.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 0.2670,
                'lng'         => 101.3800,
            ],
            [
                'id' => 8,
                'nama_unit'   => 'PKS Tandun',
                'lokasi'      => 'Kec. Tandun, Kab. Rokan Hulu, Riau',
                'ip_vpn'      => '10.14.9.11',
                'ip_wifi'     => '192.168.9.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 0.6550,
                'lng'         => 100.5800,
            ],
            [
                'id' => 9,
                'nama_unit'   => 'PKS Lubuk Dalam',
                'lokasi'      => 'Kec. Lubuk Dalam, Kab. Siak, Riau',
                'ip_vpn'      => '10.14.10.14',
                'ip_wifi'     => '192.168.10.50',
                'status_vpn'  => 'Trouble',
                'status_wifi' => 'Online',
                'lat'         => 0.6890,
                'lng'         => 101.7820,
            ],
            [
                'id' => 10,
                'nama_unit'   => 'PKS Terantam',
                'lokasi'      => 'Kec. Tapung Hulu, Kab. Kampar, Riau',
                'ip_vpn'      => '10.14.11.19',
                'ip_wifi'     => '192.168.11.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 0.7810,
                'lng'         => 100.8920,
            ],
            [
                'id' => 11,
                'nama_unit'   => 'PKS Tanah Putih',
                'lokasi'      => 'Kec. Tanah Putih, Kab. Rokan Hilir, Riau',
                'ip_vpn'      => '10.14.12.30',
                'ip_wifi'     => '192.168.12.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 1.4850,
                'lng'         => 100.9520,
            ],
            [
                'id' => 12,
                'nama_unit'   => 'PKS Sei Tapung',
                'lokasi'      => 'Kec. Tandun, Kab. Rokan Hulu, Riau',
                'ip_vpn'      => '10.14.13.22',
                'ip_wifi'     => '192.168.13.50',
                'status_vpn'  => 'Online',
                'status_wifi' => 'Online',
                'lat'         => 0.7120,
                'lng'         => 100.5100,
            ],
        ];

        // Dictionary koordinat untuk lookup bila data berasal dari database
        $coordsLookup = [
            'PKS Pabatu'         => ['lat' => 3.3150, 'lng' => 99.1620],
            'PKS Sawit Seberang' => ['lat' => 3.6820, 'lng' => 98.2430],
            'PKS Bah Jambi'      => ['lat' => 2.9810, 'lng' => 99.1360],
            'PKS Mayang'         => ['lat' => 3.0850, 'lng' => 99.3210],
            'PKS Tanah Hitam'    => ['lat' => 3.1670, 'lng' => 99.5120],
            'PKS Sei Galuh'      => ['lat' => 0.6180, 'lng' => 101.3200],
            'PKS Sei Pagar'      => ['lat' => 0.2670, 'lng' => 101.3800],
            'PKS Tandun'         => ['lat' => 0.6550, 'lng' => 100.5800],
            'PKS Lubuk Dalam'    => ['lat' => 0.6890, 'lng' => 101.7820],
            'PKS Terantam'       => ['lat' => 0.7810, 'lng' => 100.8920],
            'PKS Tanah Putih'    => ['lat' => 1.4850, 'lng' => 100.9520],
            'PKS Sei Tapung'     => ['lat' => 0.7120, 'lng' => 100.5100],
        ];

        // Jika DB memiliki data, perkaya dengan koordinat
        if ($dbUnits->isNotEmpty()) {
            $units = $dbUnits->map(function ($item, $idx) use ($coordsLookup) {
                $name = $item->nama_unit;
                $coords = $coordsLookup[$name] ?? [
                    'lat' => 0.5071 + (0.35 * cos($idx * 0.8)),
                    'lng' => 101.4478 + (0.45 * sin($idx * 0.8)),
                ];
                $item->lat = !empty($item->lat) ? (float) $item->lat : $coords['lat'];
                $item->lng = !empty($item->lng) ? (float) $item->lng : $coords['lng'];
                return $item;
            });
        } else {
            // Gunakan daftar default 12 unit lengkap
            $units = collect($defaultUnits)->map(function ($item) {
                return (object) $item;
            });
        }

        // Data Kantor Pusat Regional III (Pekanbaru)
        $kantorPusat = (object) [
            'nama'      => 'Kantor Pusat Regional III',
            'lokasi'    => 'Jl. Rambutan No. 43, Pekanbaru, Riau',
            'tipe'      => 'Regional Headquarters',
            'ip_core'   => '10.14.1.1',
            'status'    => 'Online / Active',
            'lat'       => 0.5071,
            'lng'       => 101.4478,
        ];

        // 4 Kartu Statistik (dihitung dinamis sesuai jumlah unit PKS)
        $totalUnitsCount = $units->count();
        $stats = [
            'total_unit'         => (string) $totalUnitsCount,
            'unit_sub'           => 'PKS Terintegrasi',
            'total_pc'           => (string) ($totalUnitsCount * 2),
            'pc_sub'             => 'Titik PC Timbangan',
            'total_kantor_pusat' => '1',
            'kantor_pusat_sub'   => 'Regional III Pekanbaru',
            'status_monitoring'  => '24/7',
            'monitoring_sub'     => 'Real-time Gateway',
        ];

        return view('home', compact('units', 'kantorPusat', 'stats'));
    }
}

