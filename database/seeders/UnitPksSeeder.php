<?php

namespace Database\Seeders;

use App\Models\UnitPks;
use Illuminate\Database\Seeder;

class UnitPksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
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

        foreach ($units as $unit) {
            UnitPks::updateOrCreate(
                ['nama_unit' => $unit['nama_unit']],
                $unit
            );
        }
    }
}
